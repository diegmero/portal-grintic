<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the admin user from .env variables.
     */
    public function run(): void
    {
        // Get credentials from .env
        $email = env('ADMIN_EMAIL', 'clientes@grintic.com');
        $password = env('ADMIN_PASSWORD', 'Gernassder12++');
        $name = env('ADMIN_NAME', 'Admin');

        // Create super_admin role if it doesn't exist
        $role = Role::firstOrCreate(
            ['name' => 'super_admin', 'guard_name' => 'web']
        );

        // Create or update the admin user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }

        $this->command->info("✅ Admin user created/updated: {$email}");
    }
}
