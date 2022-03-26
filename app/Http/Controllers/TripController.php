<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trip\TripCreateRequest;
use App\Http\Resources\TripResource;
use App\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TripResource::collection($request->user()->trips()->latest('date')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return TripResource|\Illuminate\Http\Response
     */
    public function store(TripCreateRequest $request)
    {
        $trip = Trip::make($request->all());
        //user_id isn't fillable, seems this a bit cleaner than an array merge or something.
        $trip->user_id = $request->user()->id;
        $trip->save();
        return new TripResource($trip);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Trip  $trip
     * @return TripResource|\Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        return new TripResource($trip);

    }
}
