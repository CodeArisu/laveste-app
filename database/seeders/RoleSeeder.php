<?php

namespace Database\Seeders;

use App\Enum\UserRoles;
use App\Models\Auth\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingRoles = Role::pluck('role_name')->toArray();
        $allRoles = array_map(fn($role) => $role->label(), UserRoles::cases());

        if (count(array_diff($allRoles, $existingRoles))) {
            foreach (UserRoles::cases() as $role) {
                DB::table('roles')->insert(['role_name' => $role->label()]);
            }
        }
    }
}
