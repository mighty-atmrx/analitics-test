<?php

namespace Database\Factories;

use App\Models\ApiServiceTokenType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiServiceTokenType>
 */
class ApiServiceTokenTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $apiServiceIds = [1, 1, 2, 2, 3, 3];
        static $tokenTypeIds = [1, 2, 1, 3, 2, 3];

        static $index = 0;

        $apiServiceId = $apiServiceIds[$index % count($apiServiceIds)];
        $tokenTypeId = $tokenTypeIds[$index % count($tokenTypeIds)];

        $index++;

        return [
            'api_service_id' => $apiServiceId,
            'token_type_id' => $tokenTypeId,
        ];
    }
}
