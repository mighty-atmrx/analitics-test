<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\Company;

class CompanyDto
{
    public function __construct(
        public int $id,
        public string $name,
    ){
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public static function fromEntity(Company $company): self
    {
        return new self (
            id: (int) ($company['id']),
            name: (string) ($company['name'])
        );
    }
}
