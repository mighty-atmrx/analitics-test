<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\TokenTypeAlreadyExistsException;
use App\Repositories\TokenTypeRepository;

class TokenTypeService extends BaseCreateService
{
    public function __construct(
        private readonly TokenTypeRepository $repository
    ){
    }

    protected function getRepository(): TokenTypeRepository
    {
        return $this->repository;
    }

    protected function getExistsExceptionClass(): string
    {
        return TokenTypeAlreadyExistsException::class;
    }
}
