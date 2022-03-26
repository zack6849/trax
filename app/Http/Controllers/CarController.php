<?php

namespace App\Http\Controllers;

use App\Car;
use App\Http\Requests\Car\CreateCarRequest;
use App\Http\Resources\CarCollectionResource;
use App\Http\Resources\CarResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CarCollectionResource|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new CarCollectionResource($request->user()->cars()->latest()->get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return CarResource|\Illuminate\Http\Response
     */
    public function store(CreateCarRequest $request)
    {
        //create the car with the request info
        $car = Car::query()->make($request->all());
        //save the car to the user's collection of cars.
        $request->user()->cars()->save($car);
        return new CarResource($car);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return CarResource|\Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return new CarResource($car);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Car $car)
    {
        $car->delete();
        if($request->expectsJson()){
            return ['message' => 'car deleted'];
        }
        return "car deleted";
    }
}
