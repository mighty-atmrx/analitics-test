<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\AccountDto;
use App\Http\Exceptions\AccountAlreadyExistsException;
use App\Repositories\AccountRepository;

readonly class AccountService
{
    public function __construct(
        private AccountRepository $repository
    ){
    }

    /**
     * @throws AccountAlreadyExistsException
     */
    public function create(array $data): AccountDto
    {
        if ($this->repository->exists($data)) {
            throw new AccountAlreadyExistsException();
        }

        return $this->repository->create($data);
    }
}
