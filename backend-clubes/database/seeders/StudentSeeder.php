<?php

namespace Database\Seeders;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Student::create([
            'rut' => '22222222-1',
            'name' => 'Juan',
            'lastname' => 'Perez',
            'age' => 15,
            'team_id' => 1,
            'level_id' => 1, // Alto Rendimiento (user_id 2, team_id 1)
        ]);

        Student::create([
            'rut' => '22222222-2',
            'name' => 'Maria',
            'lastname' => 'Gomez',
            'age' => 16,
            'team_id' => 2,
            'level_id' => 2, // Iniciación (user_id 3, team_id 2)
        ]);

        Student::create([
            'rut' => '22222222-3',
            'name' => 'Pedro',
            'lastname' => 'Lopez',
            'age' => 14,
            'team_id' => 1,
            'level_id' => 3, // Preparación (user_id 2, team_id 1)
        ]);

        Student::create([
            'rut' => '22222222-4',
            'name' => 'Ana',
            'lastname' => 'Martinez',
            'age' => 15,
            'team_id' => 1,
            'level_id' => 1,
        ]);

    }
}
