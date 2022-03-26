<?php

namespace App\Http\Resources;

use App\Car;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('m/d/Y'),
            'miles' => $this->miles,  /** Order the trips by date, and by ID, so that if more than one is entered in the same day, it still knows that it was first */
            'total' => round($this->car->user->trips()->where('date', '<=', $this->date)->orderBy('id')->sum('miles'), 1),
            'car' => new CarResource($this->car),
        ];
    }
}
