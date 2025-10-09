<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full access to all features'
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrative access with some restrictions'
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Standard user access'
            ]
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
