<?php

namespace Database\Seeders;

use App\Models\StudentsPeriod;
use Illuminate\Database\Seeder;

class StudentsPeriodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StudentsPeriod::truncate();

        for ($i = 0; $i < 150; $i++) {
            StudentsPeriod::create([
                'period_id' => rand(1, 20),
                'student_id' => rand(1, 100),
            ]);
        }
    }
}
