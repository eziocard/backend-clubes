<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
       User::create([
            'rut' => '11111111-1',
            'name' => 'admin',
            'lastname' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'team_id' => null,
            'role' => 'superuser'
        ]);

        User::create([
            'rut' => '11111111-2',
            'name' => 'valentina',
            'lastname' => 'sandoval',
            'email' => 'valentina@gmail.com',
            'password' => '123456789',
            'team_id' => null,
            'role' => 'teacher'
        ]);
    }
}
 