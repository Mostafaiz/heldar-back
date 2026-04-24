<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username' => '09123456789',
            'role' => UserRoleEnum::MANAGER->value,
            'password' => 'admin'
        ]);
    }
}
