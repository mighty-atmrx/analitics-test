<?php

namespace Database\Factories;

use App\Models\TokenType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TokenType>
 */
class TokenTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $types = ['Bearer', 'API-key', 'Login_Password'];
        static $codes = ['bearer', 'api-key', 'login_password'];

        static $index = 0;

        $type = $types[$index % count($types)];
        $code = $codes[$index % count($codes)];

        $index++;

        return [
            'type' => $type,
            'code' => $code,
        ];
    }
}
