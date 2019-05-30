<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Lesson;
use Faker\Generator as Faker;

$factory->define(Lesson::class, function (Faker $faker) {
    return [
        'tutor_id' => function () {
            return factory('App\Tutor')->create()->id;
        },
        'user_id'=> function () {
            return factory('App\User')->create()->id;
        },
        'date' => $faker->dateTimeThisYear()->format('Y-m-d'),
        'time' => $faker->time('H:00:00'),
        'durationInHours' => $faker->numberBetween(1,2),
    ];
});
