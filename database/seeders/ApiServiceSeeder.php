<?php

namespace Database\Seeders;

use App\Models\ApiService;
use Illuminate\Database\Seeder;

class ApiServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApiService::factory()->count(3)->create();
    }
}
