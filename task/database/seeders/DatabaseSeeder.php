<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            SetupSeeder::class,
        ]);

        // Create roles
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

        // Create admin user
        \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // Super Admin role
            'email_verified_at' => now(),
            'is_active' => true
        ]);

        // Create test user
        \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role_id' => 3, // User role
            'email_verified_at' => now(),
            'is_active' => true
        ]);

        $this->call([
            RoleSeeder::class,
        ]);
    }
}
