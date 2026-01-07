<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Auto-assign super_admin role to the first user.
     */
    public function created(User $user): void
    {
        // If this is the first user (and only user), make them super_admin
        if (User::count() === 1) {
            // Ensure super_admin role exists
            Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
            
            // Assign role
            $user->assignRole('super_admin');
            
            // Verify email automatically
            $user->email_verified_at = now();
            $user->save();
        }
    }
}
