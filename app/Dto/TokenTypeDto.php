<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\TokenType;

class TokenTypeDto
{
    public function __construct(
        public int $id,
        public string $type,
        public string $code,
    ){
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'code' => $this->code,
        ];
    }

    public static function fromEntity(TokenType $tokenType): self
    {
        return new self(
            id: (int) $tokenType['id'],
            type: (string) $tokenType['type'],
            code: (string) $tokenType['code'],
        );
    }
}
