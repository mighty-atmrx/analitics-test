<?php

namespace App\Http\Exceptions;

use Exception;

class ServiceNotSupportTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct('service_not_support_token');
    }
}
