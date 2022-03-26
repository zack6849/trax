<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Trip
 *
 * @property int $id
 * @property int $car_id
 * @property string $date
 * @property float $mileage
 * @property float $total_mileage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Car $car
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip newQuery()
 * @method static \Illuminate\Database\Query\Builder|Trip onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereMileage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTotalMileage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Trip withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Trip withoutTrashed()
 * @mixin \Eloquent
 * @property int $user_id
 * @property float $miles
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereMiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereUserId($value)
 * @property float $total
 * @method static \Illuminate\Database\Eloquent\Builder|Trip whereTotal($value)
 * @method static Builder|Trip inOrder()
 */
class Trip extends Model
{
    use SoftDeletes;

    public static function booted(){
        static::creating(function(Trip $trip){
            $existing_mileage = $trip->user->trips()->sum('miles');
            $trip->total = bcadd($existing_mileage, $trip->miles, 1);
            $trip->car->update(['trip_miles' => bcadd($trip->car->trip_miles, $trip->miles, 1)]);
            \Log::info("Trip being created!");
        });
    }


    protected $dates = [
        'date'
    ];

    protected $fillable = [
        'miles',
        'date',
        'car_id',
    ];

    public function car(){
        return $this->belongsTo(Car::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeInOrder(Builder $query){
        return $query->orderByDesc('date')->orderByDesc('id');
    }
}
