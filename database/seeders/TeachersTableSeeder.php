<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Teacher::truncate();

        $users = User::all();

        for ($i = 0; $i < 10; $i++) {

            $name = explode(' ', $users[$i]->name);
            $forename = $name[0];
            $surname = $name[1];

            Teacher::create([
                'name' => "$forename $surname",
                'username' => strtolower($forename) . substr($surname, 0, 1),
                'email' => $users[$i]->email,
                'password' => $users[$i]->password, // password
            ]);
        }
    }
}
