<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Role;
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
        // Buat Admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
        ]);

        // Buat Manager
        $manager = User::factory()->create([
            'name' => 'Manager User',
            'username' => 'manager',
            'email' => 'manager@example.com',
            'email_verified_at' => now(),
        ]);

        // Buat Staff
        $staff = User::factory()->create([
            'name' => 'Staff User',
            'username' => 'staff',
            'email' => 'staff@example.com',
            'email_verified_at' => now(),
        ]);

        // Jalankan Permission Seeder
        $this->call(PermissionSeeder::class);

        // Assign role
        $admin->assignRole(RoleEnum::ADMINISTRATOR->value);
        $manager->assignRole(RoleEnum::MANAGER->value);
        $staff->assignRole(RoleEnum::STAFF->value);
    }
}
