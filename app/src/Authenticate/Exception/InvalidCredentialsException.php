<?php

/*
 * UserFrosting Account Sprinkle (http://www.userfrosting.com)
 *
 * @link      https://github.com/userfrosting/sprinkle-account
 * @copyright Copyright (c) 2022 Alexander Weissman & Louis Charette
 * @license   https://github.com/userfrosting/sprinkle-account/blob/master/LICENSE.md (MIT License)
 */

namespace UserFrosting\Sprinkle\Account\Authenticate\Exception;

/**
 * Invalid credentials exception.  Used when an account fails authentication for some reason.
 */
class InvalidCredentialsException extends AuthException
{
    protected $defaultMessage = 'USER_OR_PASS_INVALID';
    protected $httpErrorCode = 403;
}
