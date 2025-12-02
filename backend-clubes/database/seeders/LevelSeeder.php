<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            'name' => 'Alto Rendimiento',
            'user_id' => 2,
            'team_id' => 1,
        ]);
        Level::create([
            'name' => 'Iniciación',
            'user_id' => 3,
            'team_id' => 2,
        ]);
        Level::create([
            'name' => 'Preparación',
            'user_id' => 2,
            'team_id' => 1,
        ]);
    }
}
