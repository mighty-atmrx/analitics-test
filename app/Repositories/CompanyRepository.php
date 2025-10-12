<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CompanyDataDto;
use App\Models\Company;

class CompanyRepository
{
    public function exists(string $name): bool
    {
        return Company::query()->where('name', $name)->exists();
    }

    public function create(string $name): CompanyDataDto
    {
        $company = Company::query()->create([
            'name' => $name,
        ]);

        return CompanyDataDto::fromEntity($company);
    }
}
