<?php

namespace App\Http\Controllers;

use App\Car;
use App\Http\Requests\Car\StoreRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarController extends Controller
{
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            Car::create($request->all());

            DB::commit();

            return response('Car stored', 200)
                ->header('Content-Type', 'text/plain');
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollback();

            return response($e->getMessage(), 500)
                ->header('Content-Type', 'text/plain');
        }
    }

    public function show($id)
    {
        return Car::find($id);
    }

    public function destroy($id)
    {
        $entry = Car::findOrFail($id);
        if($entry->delete()) {
            return Response::json([
                'message' => 'Car was deleted'
            ], 204);
        }
    }

    public function index()
    {
        return Car::all();
    }
}
