<?php

namespace Database\Seeders;

use App\Models\ApiServiceTokenType;
use Illuminate\Database\Seeder;

class ApiServiceTokenTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiServiceTokenType::factory()->count(6)->create();
    }
}
