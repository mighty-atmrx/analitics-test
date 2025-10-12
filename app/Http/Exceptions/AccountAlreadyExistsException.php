<?php

namespace App\Http\Exceptions;

use Exception;

class AccountAlreadyExistsException extends Exception
{
    public function __construct(
    ){
        parent::__construct('account_already_exists');
    }
}
