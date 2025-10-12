<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\AccountDto;
use App\Models\Account;

class AccountRepository
{
    public function exists(array $data): bool
    {
        return Account::query()
            ->where('name', $data['name'])
            ->where('company_id', $data['company_id'])
            ->exists();
    }

    public function create(array $data): AccountDto
    {
        $account = Account::query()->create($data);
        return AccountDto::fromEntity($account);
    }

    public function all(): array
    {
        $accounts = Account::query()->get();
        return $accounts->map(fn($account) => AccountDto::fromEntity($account))->toArray();
    }

    public function getById(int $id): ?Account
    {
        return Account::query()->find($id);
    }
}
