<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class MainManagerPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(1);
        $permissions = Permission::all()->pluck('id')->toArray();
        $user->permissions()->syncWithoutDetaching($permissions);
    }
}
