<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Program;
use App\Models\ProgramClass;

class ProgramClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alHaqKids = Program::where('code', 'alhaq-kids')->first();

        if ($alHaqKids) {
            $classes = [
                ['name' => 'THE SENSORY TRAVELER', 'min_age' => 4, 'max_age' => 6],
                ['name' => 'THE QURANIC EXPLORER', 'min_age' => 7, 'max_age' => 9],
                ['name' => 'THE YOUNG MUFASSIR', 'min_age' => 10, 'max_age' => 12],
            ];

            foreach ($classes as $classData) {
                ProgramClass::firstOrCreate(
                    [
                        'program_id' => $alHaqKids->id,
                        'name' => $classData['name']
                    ],
                    [
                        'min_age' => $classData['min_age'],
                        'max_age' => $classData['max_age']
                    ]
                );
            }
        }
    }
}
