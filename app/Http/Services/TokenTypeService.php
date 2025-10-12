<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\TokenTypeDto;
use App\Http\Exceptions\TokenTypeAlreadyExistsException;
use App\Repositories\TokenTypeRepository;

readonly class TokenTypeService
{
    public function __construct(
        private TokenTypeRepository $repository
    ){
    }

    /**
     * @throws TokenTypeAlreadyExistsException
     */
    public function create(array $data): TokenTypeDto
    {
        if ($this->repository->exists($data)) {
            throw new TokenTypeAlreadyExistsException();
        }

        return $this->repository->create($data);
    }
}
