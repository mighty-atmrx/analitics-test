<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\ApiServiceAlreadyExistsException;
use App\Repositories\ApiServiceRepository;

class ApiServiceManager extends BaseCreateService
{
    public function __construct(
        private readonly ApiServiceRepository $repository
    ){
    }

    protected function getRepository(): ApiServiceRepository
    {
        return $this->repository;
    }

    protected function getExistsExceptionClass(): string
    {
        return ApiServiceAlreadyExistsException::class;
    }
}
