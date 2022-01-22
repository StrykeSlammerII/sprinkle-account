<?php

/*
 * UserFrosting Account Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/sprinkle-account
 * @copyright Copyright (c) 2022 Alexander Weissman & Louis Charette
 * @license   https://github.com/userfrosting/sprinkle-account/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Expression;
use UserFrosting\Sprinkle\Account\Database\Factories\UserFactory;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\ActivityInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\GroupInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\PasswordResetInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\PermissionInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\RoleInterface;
use UserFrosting\Sprinkle\Account\Database\Models\Interfaces\UserInterface;
// use UserFrosting\Sprinkle\Account\Facades\Password;
use UserFrosting\Sprinkle\Core\Database\Models\Model;
use UserFrosting\Sprinkle\Core\Database\Relations\BelongsToManyThrough;
use UserFrosting\Sprinkle\Core\Facades\Debug;
use UserFrosting\Support\Repository\Repository as Config;

/**
 * User Class.
 *
 * Represents a User object as stored in the database.
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class User extends Model implements UserInterface
{
    use SoftDeletes;
    use HasFactory;

    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'users';

    /**
     * @var string[] The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_name',
        'first_name',
        'last_name',
        'email',
        'locale',
        'group_id',
        'flag_verified',
        'flag_enabled',
        'password',
        'deleted_at',
    ];

    /**
     * @var string[] A list of attributes to hide by default when using toArray() and toJson().
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var string[] The attributes that should be mutated to dates.
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @var string[] The accessors to append to the model's array form.
     */
    protected $appends = [
        'full_name',
    ];

    /**
     * @var array<string, string> The attributes that should be cast.
     */
    protected $casts = [
        'flag_verified' => 'boolean',
        'flag_enabled'  => 'boolean',
    ];

    /**
     * @var array<string, string> Events used to handle the user object cache on update and deletion.
     */
    protected $dispatchesEvents = [
        // 'saved'   => Events\DeleteUserCacheEvent::class, // TODO
        // 'deleted' => Events\DeleteUserCacheEvent::class, // TODO
    ];

    /**
     * Cached dictionary of permissions for the user.
     *
     * @var array
     */
    protected $cachedPermissions;

    /**
     * Delete this user from the database, along with any linked roles and activities.
     *
     * @param bool $hardDelete Set to true to completely remove the user and all associated objects.
     *
     * @return bool true if the deletion was successful, false otherwise.
     */
    public function delete($hardDelete = false)
    {
        if ($hardDelete) {

            /** @var \UserFrosting\Sprinkle\Core\Util\ClassMapper $classMapper */
            $classMapper = static::$ci->classMapper;

            // Remove all role associations
            $this->roles()->detach();

            // Remove last activity association
            $this->lastActivity()->dissociate();
            $this->save();

            // Remove all user tokens
            $this->activities()->delete();
            $this->passwordResets()->delete();
            $classMapper->getClassMapping('verification')::where('user_id', $this->id)->delete();
            $classMapper->getClassMapping('persistence')::where('user_id', $this->id)->delete();

            // Delete the user
            return $this->forceDelete();
        }

        // Soft delete the user, leaving all associated records alone
        return parent::delete();
    }

    /**
     * Allows you to get the full name of the user using `$user->full_name`.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Allows you to get the user's avatar using `$user->avatar`.
     *
     * Use Gravatar as the user avatar provider.
     *
     * @return string
     */
    public function getAvatarAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $hash . '?d=mm';
    }

    /**
     * Attribute alias for lastActivity() method. Can be accessed using `$user->last_activity`.
     *
     * @return Activity|null
     */
    public function getLastActivityAttribute(): ?Activity
    {
        return $this->lastActivity();
    }

    /**
     * Return a cache instance specific to that user.
     *
     * @return \Illuminate\Contracts\Cache\Store
     */
    public function getCache()
    {
        return static::$ci->cache->tags('_u' . $this->id);
    }

    /**
     * Retrieve the cached permissions dictionary for this user.
     *
     * @return array
     */
    public function getCachedPermissions()
    {
        if (!isset($this->cachedPermissions)) {
            $this->reloadCachedPermissions();
        }

        return $this->cachedPermissions;
    }

    /**
     * Retrieve the cached permissions dictionary for this user.
     *
     * @return User
     */
    public function reloadCachedPermissions()
    {
        $this->cachedPermissions = $this->buildPermissionsDictionary();

        return $this;
    }

    /**
     * Returns whether or not this user is the master user.
     *
     * @return bool
     */
    public function isMaster(): bool
    {
        /** @var Config */
        $config = static::$ci->get(Config::class);
        $masterId = intval($config->get('reserved_user_ids.master'));

        // Need to use loose comparison for now, because some DBs return `id` as a string
        return $this->id == $masterId;
    }

    /**
     * Get all activities for this user.
     *
     * @return HasMany
     */
    public function activities(): HasMany
    {
        /** @var string */
        $relation = static::$ci->get(ActivityInterface::class);

        return $this->hasMany($relation, 'user_id');
    }

    /**
     * Get the most recent activity for this user.
     *
     * @param string|null $type The type of activity to search for.
     *
     * @return Activity|null
     */
    public function lastActivity(?string $type = null): ?Activity
    {
        $query = $this->activities();
        if (!is_null($type)) {
            // @phpstan-ignore-next-line Laravel is bad at type hinting
            $query = $query->forType($type);
        }

        // @phpstan-ignore-next-line Laravel is bad at type hinting
        return $query->orderBy('occurred_at', 'desc')->first();
    }

    /**
     * Get the most recent time for a specified activity type for this user.
     *
     * @param string|null $type The type of activity to search for.
     *
     * @return DateTime|null The last activity time, as a DateTime, or null if an activity of this type doesn't exist.
     */
    public function lastActivityTime(?string $type = null): ?DateTime
    {
        return $this->lastActivity($type)?->occurred_at;
    }

    /**
     * Get the amount of time, in seconds, that has elapsed since the last activity of a certain time for this user.
     *
     * @param string|null $type The type of activity to search for.
     *
     * @return int
     */
    public function getSecondsSinceLastActivity(?string $type = null): int
    {
        $time = $this->lastActivityTime($type) ?? '0000-00-00 00:00:00';
        $time = new Carbon($time);

        return $time->diffInSeconds();
    }

    /**
     * Joins the user's most recent activity directly, so we can do things like sort, search, paginate, etc. in Sprunje.
     *
     * @param Builder $query
     *
     * @return Builder|QueryBuilder
     */
    public function scopeJoinLastActivity(Builder $query): Builder|QueryBuilder
    {
        return $query->select('users.*', new Expression('MAX(activities.occurred_at) as last_activity'))
                     ->join('activities', 'activities.user_id', '=', 'users.id')
                     ->groupBy('users.id');
    }

    /**
     * Return this user's group.
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        /** @var string */
        $relation = static::$ci->get(GroupInterface::class);

        return $this->belongsTo($relation, 'group_id');
    }

    /**
     * Performs tasks to be done after this user has been successfully authenticated.
     *
     * By default, adds a new sign-in activity and updates any legacy hash.
     *
     * @param mixed[] $params Optional array of parameters used for this event handler.
     *
     * @todo Transition to Laravel Event dispatcher to handle this
     */
    public function onLogin($params = [])
    {
        // Add a sign in activity (time is automatically set by database)
        static::$ci->userActivityLogger->info("User {$this->user_name} signed in.", [
            'type' => 'sign_in',
        ]);

        // Update password if we had encountered an outdated hash
        $passwordType = Password::getHashType($this->password);

        if ($passwordType != 'modern') {
            if (!isset($params['password'])) {
                Debug::notice('Notice: Unhashed password must be supplied to update to modern password hashing.');
            } else {
                // Hash the user's password and update
                $passwordHash = Password::hash($params['password']);
                if ($passwordHash === null) {
                    Debug::notice('Notice: outdated password hash could not be updated because the new hashing algorithm is not supported.');
                } else {
                    $this->password = $passwordHash;
                    Debug::notice('Notice: outdated password hash has been automatically updated to modern hashing.');
                }
            }
        }

        // Save changes
        $this->save();

        return $this;
    }

    /**
     * Performs tasks to be done after this user has been logged out.
     *
     * By default, adds a new sign-out activity.
     *
     * @param mixed[] $params Optional array of parameters used for this event handler.
     *
     * @todo Transition to Laravel Event dispatcher to handle this
     */
    public function onLogout($params = [])
    {
        static::$ci->userActivityLogger->info("User {$this->user_name} signed out.", [
            'type' => 'sign_out',
        ]);

        return $this;
    }

    /**
     * Get all password reset requests for this user.
     *
     * @return HasMany
     */
    public function passwordResets(): HasMany
    {
        /** @var string */
        $relation = static::$ci->get(PasswordResetInterface::class);

        return $this->hasMany($relation, 'user_id');
    }

    /**
     * Get all of the permissions this user has, through its roles.
     *
     * @return BelongsToManyThrough
     */
    public function permissions(): BelongsToManyThrough
    {
        /** @var string */
        $permissionRelation = static::$ci->get(PermissionInterface::class);

        /** @var string */
        $roleRelation = static::$ci->get(RoleInterface::class);

        return $this->belongsToManyThrough(
            $permissionRelation,
            $roleRelation,
            'role_users',
            'user_id',
            'role_id',
            'permission_roles',
            'role_id',
            'permission_id'
        );
    }

    /**
     * Get all roles to which this user belongs.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        /** @var string */
        $relation = static::$ci->get(RoleInterface::class);

        return $this->belongsToMany($relation, 'role_users', 'user_id', 'role_id')->withTimestamps();
    }

    /**
     * Query scope to get all users who have a specific role.
     *
     * @param Builder $query
     * @param int     $roleId
     *
     * @return Builder
     */
    public function scopeForRole($query, $roleId)
    {
        return $query->join('role_users', function ($join) use ($roleId) {
            $join->on('role_users.user_id', 'users.id')
                 ->where('role_id', $roleId);
        });
    }

    /**
     * Loads permissions for this user into a cached dictionary of slugs -> arrays of permissions,
     * so we don't need to keep requerying the DB for every call of checkAccess.
     *
     * @return array
     */
    protected function buildPermissionsDictionary()
    {
        $permissions = $this->permissions()->get();
        $cachedPermissions = [];

        foreach ($permissions as $permission) {
            $cachedPermissions[$permission->slug][] = $permission;
        }

        return $cachedPermissions;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
