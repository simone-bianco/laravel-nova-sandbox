<?php

namespace Database\Seeders\Models;

use App\Models\Interfaces\UserRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        UserFactory::new()->createMany([
            [
                'name' => 'admin',
                'email' => 'info@dnafactory.it',
                'role' => UserRoles::ROLE_ADMIN,
                'password' => Hash::make('DNApassword1234'),
            ],
            [
                'name' => 'guest',
                'email' => 'guest@dnafactory.it',
                'role' => UserRoles::ROLE_USER,
                'password' => Hash::make('DNApassword1234'),
            ],
        ]);
    }
}
