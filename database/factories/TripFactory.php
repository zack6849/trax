<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Car;
use App\Trip;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    $trip_mileage = $faker->randomFloat(1, 0, 150);
    $random_car = Car::inRandomOrder()->first();
    $user = $random_car->user;
    return [
        'car_id' => $random_car->id,
        'user_id' => $user->id,
        'date' => $faker->date(),
        'miles' => $trip_mileage,
    ];
});
