<?php
namespace Database\Seeders;
use App\Models\User; use Illuminate\Database\Seeder; use Illuminate\Support\Facades\Hash; use RuntimeException;
class SuperAdminSeeder extends Seeder {

public function run(): void
{
    $email    = config('app.super_admin.email');
    $password = config('app.super_admin.password');
    $name     = config('app.super_admin.name');

    if (!$email || !$password) {
        throw new \RuntimeException('Set SUPER_ADMIN_EMAIL and SUPER_ADMIN_PASSWORD before seeding.');
    }

    User::updateOrCreate(
        ['email' => $email],
        [
            'company_id' => null,
            'name'       => $name,
            'password'   => Hash::make($password),
            'role'       => 'super_admin',
        ]
    );
}


}
