<?php

declare(strict_types=1);

namespace App\Dto;

class TokenCreateDto
{
    public function __construct(
        public int $account_id,
        public int $api_service_id,
        public int $token_type_id,
        public string $token,
    ){
    }

    public function toArray(): array
    {
        return [
            'account_id' => $this->account_id,
            'api_service_id' => $this->api_service_id,
            'token_type_id' => $this->token_type_id,
            'token' => $this->token,
        ];
    }

    public function fromEntity(): self
    {
        return new self(
            $this->account_id,
            $this->api_service_id,
            $this->token_type_id,
            $this->token,
        );
    }
}
