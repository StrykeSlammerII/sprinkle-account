<?php

/*
 * UserFrosting Account Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/sprinkle-account
 * @copyright Copyright (c) 2022 Alexander Weissman & Louis Charette
 * @license   https://github.com/userfrosting/sprinkle-account/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Database\Factories;

use UserFrosting\Sprinkle\Account\Database\Models\Verification;
use UserFrosting\Sprinkle\Core\Database\Factories\Factory;

class VerificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Verification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hash' => $this->faker->word(),
        ];
    }
}
