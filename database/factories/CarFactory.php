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
    ];
});

$factory->afterCreating(Car::class, function(Car $car, Faker $faker){
    factory(Trip::class, $faker->numberBetween(3,20))->create([
        'car_id' => $car,
    ]);
});
