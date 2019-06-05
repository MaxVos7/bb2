<?php

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
        // 5 tutors
        // 3 users / tutor
        // 10 lessons / user

        $tutors = factory('App\Tutor', 5)->create();
    }
}
