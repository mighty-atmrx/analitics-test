<?php

namespace App\Http\Exceptions;

use Exception;

class CompanyNameIsTakenException extends Exception
{
    public function __construct()
    {
        parent::__construct('name_is_taken_by_another_company');
    }
}
