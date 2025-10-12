<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Exceptions\CompanyNameIsTakenException;
use App\Repositories\CompanyRepository;

class CompanyService extends BaseCreateService
{
    public function __construct(
        private readonly CompanyRepository $repository
    ){
    }

    protected function getRepository(): CompanyRepository
    {
        return $this->repository;
    }

    protected function getExistsExceptionClass(): string
    {
        return CompanyNameIsTakenException::class;
    }

    public function create(array $data)
    {
        $name = $data['name'] ?? null;
        if (!$name) {
            throw new \InvalidArgumentException('Company name is required');
        }

        if ($this->repository->exists($name)) {
            $exceptionClass = $this->getExistsExceptionClass();
            throw new $exceptionClass();
        }

        return $this->repository->create($name);
    }
}
