<?php

namespace App\Http\Exceptions;

use Exception;

class AccountNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("account_not_found_with_id_{$id}");
    }
}
