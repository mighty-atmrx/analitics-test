<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\ApiServiceDto;
use App\Models\ApiService;

class ApiServiceRepository
{
    public function exists(array $data): bool
    {
        return ApiService::query()->where('name', $data['name'])->exists();
    }

    public function create(array $data): ApiServiceDto
    {
        $service = ApiService::query()->create($data);
        return ApiServiceDto::fromEntity($service);
    }
}
