<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entity;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class EntitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Entity::truncate();

        $users = User::all();

        for ($i = 0; $i < 110; $i++) {

            if ($i < 10) {
                Entity::create([
                    'user_id' => $users[$i]->id,
                    'model_id' => $users[$i]->id,
                    'model_type' => Teacher::class
                ]);
            } else {
                Entity::create([
                    'user_id' => $users[$i]->id,
                    'model_id' => ($i - 9),
                    'model_type' => Student::class
                ]);
            }
        }
    }
}
