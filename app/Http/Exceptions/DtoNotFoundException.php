<?php

namespace App\Http\Exceptions;

use Exception;

class DtoNotFoundException extends Exception
{

    /**
     * @param string $endpoint
     */
    public function __construct(string $endpoint)
    {
        parent::__construct("dto_not_found_for_$endpoint");
    }
}
