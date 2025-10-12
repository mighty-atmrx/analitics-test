<?php

namespace App\Repositories;

use App\Dto\ApiServiceTokenTypeDto;
use App\Http\Exceptions\ApiServiceTokenTypeAlreadyExistsException;
use App\Models\ApiServiceTokenType;

class ApiServiceTokenTypeRepository
{
    public function exists(int $tokenTypeId, int $apiServiceId): bool
    {
        return ApiServiceTokenType::query()
            ->where('api_service_id', $apiServiceId)
            ->where('token_type_id', $tokenTypeId)
            ->exists();
    }

    /**
     * @throws ApiServiceTokenTypeAlreadyExistsException
     */
    public function create(array $data): ApiServiceTokenTypeDto
    {
        if ($this->exists($data['api_service_id'], $data['token_type_id'])) {
            throw new ApiServiceTokenTypeAlreadyExistsException();
        }
        $result = ApiServiceTokenType::query()->create($data);

        return ApiServiceTokenTypeDto::fromEntity($result);
    }
}
