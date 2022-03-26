<?php

namespace Tests\Feature;

use App\Http\Resources\CarCollectionResource;
use App\Http\Resources\CarResource;
use Tests\TestCase;

class CarsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanViewCars()
    {
        $this->actingAs($this->user, 'api');
        $resource = new CarCollectionResource($this->user->cars);
        $response = $this->json('get', route('cars.index'));
        //ensure we have a valid response
        $response->assertStatus(200);
        //ensure we get a collection back
        $response->assertJson($resource->response()->getData(true));
        //ensure the structure is what we expect
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'make',
                    'model',
                    'year',
                ]
            ]
        ]);
    }

    public function testCanDeleteOwnCars(){
        $this->actingAs($this->user, 'api');
        $own_car = $this->user->cars->first();
        $car_count = $this->user->cars()->count();
        $response = $this->json('delete', route('cars.destroy', $own_car));
        $response->assertStatus(200);
        $response->assertJson(['message' => 'car deleted']);
        //assert we have one fewer car.
        $this->assertEquals($this->user->cars()->count(), $car_count - 1);
    }

    public function testCantDeleteOthersCars(){
        $this->actingAs($this->other_user, 'api');
        $someone_elses_car = $this->user->cars->first();
        $response = $this->json('delete', route('cars.destroy', $someone_elses_car));
        $response->assertStatus(403);
    }

    public function testCanCreateCar(){
        $this->actingAs($this->user, 'api');
        $response = $this->json('post', route('cars.store'), [
            'make' => 'Tesla',
            'model' => 'Model X',
            'year' => '2021'
        ]);
        //this seems stupid but this test sometimes fails, I think it's a race condition where the new data somehow hasn't been saved to the DB before responding?
        sleep(1);
        $new_car = $this->user->cars()->latest()->first();
        $response->assertStatus(201);
        $response->assertJson((new CarResource($new_car))->response()->getData(true));
    }

    public function testCantCreateCarsAnonymously(){
        $response = $this->json('post', route('cars.store'), [
            'make' => 'Tesla',
            'model' => 'Model X',
            'year' => '2021'
        ]);
        $response->assertStatus(401);
    }

    public function testCantCreateCarsBeforeTheyExisted(){
        $this->actingAs($this->user, 'api');
        $response = $this->json('post', route('cars.store'), [
            'make' => 'Horse',
            'model' => 'Wagon',
            'year' => 1700,
        ]);
        $response->assertStatus(422);
    }

    public function testCarTotalTripsAccurate()
    {
        $cars = $this->user->cars;
        foreach ($cars as $car){
            $miles_traveled_in_this_car = $car->trips()->sum('miles');
            $this->assertEquals($car->trip_miles, $miles_traveled_in_this_car);
        }
    }

}
