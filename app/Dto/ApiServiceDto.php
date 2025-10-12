<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\ApiService;

class ApiServiceDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code
    ){
    }

    public function toArray(): array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'code' => $this->code,
        ];
    }

    public static function fromEntity(ApiService $service): self
    {
        return new self(
            id: (int) $service['id'],
            name: (string) $service['name'],
            code: (string) $service['code'],
        );
    }
}
