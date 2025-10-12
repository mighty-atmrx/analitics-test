<?php

namespace App\Http\Exceptions;

use Exception;

class LoginPasswordRequiredException extends Exception
{
    public function __construct()
    {
        parent::__construct('login_password_is_required');
    }
}
