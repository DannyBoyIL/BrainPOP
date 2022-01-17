<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(110)->create();

        $this->call(PeriodsTableSeeder::class);
        $this->call(TeachersTableSeeder::class);
        $this->call(StudentsTableSeeder::class);
        $this->call(TeachersPeriodsTableSeeder::class);
        $this->call(StudentsPeriodsTableSeeder::class);
        $this->call(EntitiesTableSeeder::class);
    }
}
