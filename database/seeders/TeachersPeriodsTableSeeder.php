<?php

namespace Database\Seeders;

use App\Models\TeachersPeriod;
use Illuminate\Database\Seeder;

class TeachersPeriodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeachersPeriod::truncate();

        for ($i = 0; $i < 20; $i++) {
            TeachersPeriod::create([
                'period_id' => $i + 1,
                'teacher_id' => rand(1, 10),
            ]);
        }
    }
}
