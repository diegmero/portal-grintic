<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class FixAdminUser extends Command
{
    protected $signature = 'admin:fix {email}';

    protected $description = 'Fix admin user: verify email and assign super_admin role';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Debug: show all users
        $allUsers = User::all(['id', 'email']);
        $this->info("Found " . $allUsers->count() . " users in database:");
        foreach ($allUsers as $u) {
            $this->line("  - [{$u->id}] {$u->email}");
        }
        
        $user = User::where('email', $email)->first();
        
        // If not found, try with quotes (Plesk bug)
        if (!$user) {
            $user = User::where('email', '"' . $email . '"')->first();
            if ($user) {
                // Fix the email by removing quotes
                $user->email = $email;
                $this->info("🔧 Fixed email (removed quotes)");
            }
        }
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        // Fix name if it has quotes
        if (str_starts_with($user->name, '"') && str_ends_with($user->name, '"')) {
            $user->name = trim($user->name, '"');
            $this->info("🔧 Fixed name (removed quotes)");
        }

        // Verify email
        $user->email_verified_at = now();
        $user->save();
        $this->info("✅ Email verified for {$email}");

        // Create super_admin role if not exists
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $this->info("✅ super_admin role ensured");

        // Assign role
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
            $this->info("✅ super_admin role assigned to {$email}");
        } else {
            $this->info("ℹ️ User already has super_admin role");
        }

        $this->info("🎉 Done! User can now access /admin");
        return 0;
    }
}
