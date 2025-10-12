<?php

namespace App\Dto;

use App\Models\ApiServiceTokenType;

class ApiServiceTokenTypeDto
{
    public function __construct(
        public int $id,
        public int $api_service_id,
        public int $token_type_id,
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'api_service_id' => $this->api_service_id,
            'token_type_id' => $this->token_type_id,
        ];
    }

    public static function fromEntity(ApiServiceTokenType $entity): self
    {
        return new self (
            id: $entity['id'],
            api_service_id: $entity['api_service_id'],
            token_type_id: $entity['token_type_id'],
        );
    }
}
