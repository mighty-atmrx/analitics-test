<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\AccountAlreadyExistsException;
use App\Repositories\AccountRepository;

class AccountService extends BaseCreateService
{
    public function __construct(
        private readonly AccountRepository $repository
    ){
    }

    protected function getRepository(): AccountRepository
    {
        return $this->repository;
    }

    protected function getExistsExceptionClass(): string
    {
        return AccountAlreadyExistsException::class;
    }
}
