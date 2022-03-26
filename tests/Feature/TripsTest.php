<?php

namespace Tests\Feature;

use App\Http\Resources\TripCollectionResource;
use App\Jobs\RecalculateTripTotals;
use App\Trip;
use Tests\TestCase;

class TripsTest extends TestCase
{
    public function testCanViewTrips(){
        $this->actingAs($this->user, 'api');
        $response = $this->json('get', route('trips.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'date',
                    'miles',
                    'total',
                    'car' => [
                        'id',
                        'make',
                        'model',
                        'year',
                        'trip_count',
                        'trip_miles',
                    ]
                ]
            ]
        ]);
        //assure that our response is using our resource collection
        $resource = new TripCollectionResource($this->user->trips()->with('car.trips')->orderByDesc('date')->orderByDesc('id')->get());
        $response->assertJson($resource->response()->getData(true));
    }

    public function testCanCreateTrip(){
        $this->actingAs($this->user, 'api');
        $my_car = $this->user->cars()->first();
        $response = $this->json('post', route('trips.store'), [
            'car_id' => $my_car->id,
            'miles' => 10,
            'date' => now()
        ]);
        $response->assertStatus(201);

    }

    public function testCantCreateTripsInTheFuture(){
        $this->actingAs($this->user, 'api');
        $my_car = $this->user->cars()->first();
        $response = $this->json('post', route('trips.store'), [
            'car_id' => $my_car->id,
            'miles' => 10,
            'date' => now()->addDays(1)
        ]);
        $response->assertStatus(422);
    }

    public function testTripTotalsAccurate(){
        /** @var Trip $trip */
        $trip = $this->user->trips()->latest('date')->orderBy('id', 'desc')->first();
        $this->assertEquals($trip->total, $this->user->trips()->sum('miles'));
    }

    public function testTripTotalsAccurateIfCarDeleted(){
        $car = $this->user->cars()->inRandomOrder()->first();
        $old_amount = $this->user->trips()->sum('miles');
        $car->delete();
        dispatch_now(new RecalculateTripTotals($this->user));
        $new_amount = $this->user->trips()->sum('miles');
        $this->assertNotEquals($old_amount, $new_amount);
        /** @var Trip $trip */
        $trip = $this->user->trips()->latest('date')->orderBy('id', 'desc')->first();
        $this->assertEquals($this->user->trips()->sum('miles'), $trip->total);
    }

}
