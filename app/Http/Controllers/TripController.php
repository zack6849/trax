<?php

namespace App\Http\Controllers;

use App\Car;
use App\Http\Requests\Trip\StoreRequest;
use App\Trip;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripController extends Controller
{
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            Trip::create($request->all());

            DB::commit();

            return response('Trip stored', 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollback();

            return response($e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function getTrips()
    {
        return Trip::with('cars')->get();
    }
}
