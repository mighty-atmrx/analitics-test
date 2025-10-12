<?php

declare(strict_types=1);

namespace App\Http\Exceptions;

use Exception;

class ApiServiceAlreadyExistsException extends Exception
{
    public function __construct()
    {
        parent::__construct('api_service_already_exists');
    }
}
