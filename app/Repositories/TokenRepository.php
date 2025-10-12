<?php

namespace App\Repositories;

use App\Models\Token;

class TokenRepository
{
    public function checkToken(string $token, int $tokenTypeId, int $apiServiceId): bool
    {
        return Token::query()
            ->where('token', $token)
            ->where('token_type_id', $tokenTypeId)
            ->where('api_service_id', $apiServiceId)
            ->exists();
    }

    public function create(array $data): void
    {
        Token::query()->create($data);
    }
}
