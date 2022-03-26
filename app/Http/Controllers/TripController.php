<?php

namespace App\Http\Controllers;

use App\Http\Requests\Trip\TripCreateRequest;
use App\Http\Resources\TripResource;
use App\Trip;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TripResource::collection($request->user()->trips()->with('car.trips')->inOrder()->get());
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
}
