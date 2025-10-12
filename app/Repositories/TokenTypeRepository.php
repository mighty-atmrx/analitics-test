<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\TokenTypeDto;
use App\Enum\TokenTypeEnum;
use App\Models\TokenType;
use RuntimeException;

class TokenTypeRepository
{
    public function findByType(TokenTypeEnum $tokenType): int
    {
        $model = TokenType::query()->where('code', $tokenType->value)->first();

        if (!$model) {
            throw new RuntimeException("Token type '{$tokenType->value}' not found in database.");
        }

        return $model->id;
    }

    public function findById(int $id): string
    {
        $token = TokenType::query()->find($id);
        if (!$token) {
            throw new RuntimeException("Token type ID {$id} not found");
        }

        return $token->code;
    }

    public function exists(array $data): bool
    {
        return TokenType::query()->where('type', $data['type'])->exists();
    }

    public function create(array $data): TokenTypeDto
    {
        $tokenType = TokenType::query()->create($data);
        return TokenTypeDto::fromEntity($tokenType);
    }
}
