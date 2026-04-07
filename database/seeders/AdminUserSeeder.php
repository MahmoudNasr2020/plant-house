<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@planthouse.qa'],
            [
                'name'      => 'مدير النظام',
                'email'     => 'admin@planthouse.qa',
                'password'  => 'admin123456',
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );
    }
}
