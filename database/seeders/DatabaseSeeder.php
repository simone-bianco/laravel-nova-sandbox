<?php

namespace Database\Seeders;

use Database\Seeders\Models\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(Models\AutomationSeeder::class);
        $this->call(Models\MunicipalitiesSeeder::class);
    }
}
