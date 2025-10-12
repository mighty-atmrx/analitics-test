<?php

declare(strict_types=1);

namespace App\Dto;

use App\Models\Account;

class AccountDto
{
    public function __construct(
        public int $id,
        public int $company_id,
        public string $name,
        public ?string $description,
    ){
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public static function fromEntity(Account $account): self
    {
        return new self(
            id: (int) $account['id'],
            company_id: (int) $account['company_id'],
            name: (string) $account['name'],
            description: $account['description'] !== null ? (string) $account['description'] : null,
        );
    }
}
