<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Car;
use App\Trip;
use App\User;
use Faker\Generator as Faker;

$factory->define(Trip::class, function (Faker $faker) {
    $car = Car::query()->inRandomOrder()->firstOrFail();
    $trip_mileage = $faker->randomFloat(1, 0, 150);
    $oldest_existing_trip = $car->user->trips()->latest('date')->first()->date ?? now()->subYears(2);
    return [
        'car_id' => $car->id,
        'user_id' => $car->user->id,
        'date' => $oldest_existing_trip->addDays($faker->numberBetween(1,14))->setTime($faker->numberBetween(1,23),$faker->numberBetween(1,59)),
        'miles' => $trip_mileage,
    ];
});
