<?php

namespace Database\Factories;

use App\Models\ApiService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApiService>
 */
class ApiServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $names = ['Wildberries', 'Ozon', 'Yandex'];
        static $codes = ['wb', 'ozon', 'yandex'];

        static $index = 0;

        $name = $names[$index % count($names)];
        $code = $codes[$index % count($codes)];

        $index++;

        return [
            'name' => $name,
            'code' => $code,
        ];
    }
}
