<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Pages\Auth\Register as BaseRegister;
use Spatie\Permission\Models\Role;

class Register extends BaseRegister
{
    protected function afterCreate(): void
    {
        // Ensure super_admin role exists
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Assign super_admin to the newly created user (first user)
        $user = $this->getUser();
        
        if ($user instanceof User && !$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }

        // Also verify email automatically for first admin
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }
    }
}
