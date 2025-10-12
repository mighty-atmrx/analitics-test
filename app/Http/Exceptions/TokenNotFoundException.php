<?php

namespace App\Http\Exceptions;

use Exception;

class TokenNotFoundException extends Exception
{
    public function __construct()
    {
        parent::__construct('token_not_found');
    }
}
