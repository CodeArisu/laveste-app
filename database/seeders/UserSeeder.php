<?php

namespace Database\Seeders;

use App\Enum\UserRoles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => 'Roland',
                'email' => 'roland11@email.com',
                'password' => Hash::make('adminadmin'),
                'role_id' => UserRoles::ADMINISTRATOR->value,
            ],
            [
                'name' => 'Shienna',
                'email' => 'shienna12@email.com',
                'password' => Hash::make('adminadmin'),
                'role_id' => UserRoles::ADMINISTRATOR->value,
            ],
            [
                'name' => 'Angel',
                'email' => 'angel13@email.com',
                'password' => Hash::make('adminadmin'),
                'role_id' => UserRoles::ADMINISTRATOR->value,
            ],
            [
                'name' => 'testmanager',
                'email' => 'manager@email.com',
                'password' => Hash::make('manager'),
                'role_id' => UserRoles::MANAGER->value,
            ],
            [
                'name' => 'testaccountant',
                'email' => 'accountant@email.com',
                'password' => Hash::make('accountant'),
                'role_id' => UserRoles::ACCOUNTANT->value,
            ],
        ];

        foreach ($attributes as $attribute) {
            DB::table('users')->insert([
                $attribute
            ]);
        }
    }
}
