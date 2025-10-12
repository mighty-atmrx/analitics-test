<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\ApiServiceDto;
use App\Http\Exceptions\ApiServiceAlreadyExistsException;
use App\Repositories\ApiServiceRepository;

readonly class ApiServiceService
{
    public function __construct(
        private ApiServiceRepository $repository
    ){
    }

    /**
     * @throws ApiServiceAlreadyExistsException
     */
    public function create(array $data): ApiServiceDto
    {
        if ($this->repository->exists($data)) {
            throw new ApiServiceAlreadyExistsException();
        }

        return $this->repository->create($data);
    }
}
