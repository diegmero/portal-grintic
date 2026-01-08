<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'admin@test.com';
$password = 'password';

$user = User::firstOrNew(['email' => $email]);
$user->name = 'Admin Test';
// If the model casts 'password' => 'hashed', we should set plain text.
// We will check the casts array on the instance to be sure.
if (isset($user->getCasts()['password']) && $user->getCasts()['password'] === 'hashed') {
    $user->password = $password; 
    echo "Model has 'hashed' cast. Setting plain password.\n";
} else {
    $user->password = bcrypt($password);
    echo "Model has NO 'hashed' cast. Manually hashing password.\n";
}
$user->email_verified_at = now();
$user->save();

// Ensure Role exists and Assign
$role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
$user->assignRole($role);

echo "User created/updated:\n";
echo "Email: $email\n";
echo "Password: $password\n";
echo "Role: super_admin\n";
