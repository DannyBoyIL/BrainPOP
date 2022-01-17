<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\User;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Student::truncate();

        $faker = Factory::create();
        $users = User::all();

        for ($i = 10; $i < 110; $i++) {

            $name = explode(' ', $users[$i]->name);
            $forename = $name[0];
            $surname = $name[1];

            Student::create([
                'name' => "$forename $surname",
                'username' => strtolower($forename) . substr($surname, 0, 1),
                'password' => $users[$i]->password, // password
                'grade' => $faker->numberBetween(0, 12),
            ]);
        }
    }
}
