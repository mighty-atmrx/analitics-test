<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use Exception;

class TokenTypeAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('token_type_already_exists');
    }
}
