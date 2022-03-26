<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Car;
use App\Trip;
use Faker\Generator as Faker;
//this could probably be loaded from a testing fixture of some sort, but a hardcoded array is fine for now.
$cars = [
    'Ford' => [
        'Fiesta',
        'Taurus',
        'F150',
        'Ranger',
        'Raptor',
        'Ranchero'
    ],
    'Chevrolet' => [
        'Malibu',
        'TrailBlazer',
        'Blazer',
        'Cruze',
        'Silverado',
        'Suburban',
        'Colorado',
    ],
    'Toyota' => [
        'Camry',
        'Corolla',
        'Tacoma',
        'Tundra',
        'Rav4',
        '4runner',
        'FJ Cruiser',
        'Supra'
    ],
    'Honda' => [
        'Ridgeline',
        'Civic',
        'Accord',
        'Pilot',
    ],
    'Kia' => [
        'Soul',
        'Optima',
    ],
    'Nissan' => [
        'Rogue',
        'Pathfinder',
        'Armada',
        'Frontier',
        'Titan',
    ],
    'Dodge' => [
        'Ram',
        'Caravan',
        'Charger',
        'Challenger',
        'Durango'
    ],
];

$factory->define(Car::class, function (Faker $faker) use ($cars) {
    $make = $faker->randomElement(array_keys($cars));
    return [
        'make' => $make,
        'model' => $faker->randomElement($cars[$make]),
        'year' => $faker->numberBetween(1990,2021),
        'trip_miles' => 0,
    ];
});

$factory->afterCreating(Car::class, function(Car $car, Faker $faker){
    $car->user->refresh();
    if($car->user->trips()->count() > 0){
        $most_recent_trip = $car->user->trips()->latest('id')->firstOrFail();
        $this_trip_date = $most_recent_trip->date->addDays($faker->numberBetween(2,14))->setTime($faker->numberBetween(1,23),$faker->numberBetween(1,59));
    }else{
        //default "first" trip should be a random date between one or three years ago
        $this_trip_date = now()->subYears($faker->numberBetween(1,3))
            ->subDays($faker->numberBetween(1,10))
            ->setTime($faker->numberBetween(1,23),$faker->numberBetween(1,59));
    }
    factory(Trip::class, $faker->numberBetween(3,20))->create([
        'car_id' => $car,
        'user_id' => $car->user_id,
        'date' => $this_trip_date,
    ]);
});
