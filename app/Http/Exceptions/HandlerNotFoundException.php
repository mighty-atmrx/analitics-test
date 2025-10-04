<?php

namespace App\Http\Exceptions;

use Exception;

class HandlerNotFoundException extends Exception
{

    /**
     * @param string $endpoint
     */
    public function __construct(string $endpoint)
    {
        parent::__construct("handler_not_found_for_$endpoint");
    }
}
