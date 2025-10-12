<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\CompanyDto;
use App\Http\Exceptions\CompanyNameIsTakenException;
use App\Repositories\CompanyRepository;

readonly class CompanyService
{
    public function __construct(
        private CompanyRepository $repository
    ){
    }

    /**
     * @throws CompanyNameIsTakenException
     */
    public function create(string $name): CompanyDto
    {
        if ($this->repository->exists($name)) {
            throw new CompanyNameIsTakenException();
        }

        return $this->repository->create($name);
    }
}
