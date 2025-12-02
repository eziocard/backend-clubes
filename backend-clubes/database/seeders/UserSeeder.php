<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'rut' => '11111111-1',
            'name' => 'admin',
            'lastname' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'), 
            'team_id' => null,
            'role' => 'superuser'
        ]);

        User::create([
            'rut' => '11111111-2',
            'name' => 'valentina',
            'lastname' => 'sandoval',
            'email' => 'valentina@gmail.com',
            'password' => Hash::make('123456789'), 
            'team_id' => 1,
            'role' => 'teacher'
        ]);
        User::create([
            'rut' => '11111111-3',
            'name' => 'Roberto',
            'lastname' => 'sandoval',
            'email' => 'Roberto@gmail.com',
            'password' => Hash::make('123456789'), 
            'team_id' => 1,
            'role' => 'team'
        ]);
    }
}
