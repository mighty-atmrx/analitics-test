<?php

namespace App\Http\Exceptions;

use Exception;

class ApiServiceTokenTypeAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('api_service_token_type_already_exists');
    }
}
