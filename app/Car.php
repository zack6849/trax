<?php

namespace App;

use App\Jobs\RecalculateTripTotals;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Car
 *
 * @property int $id
 * @property int $user_id
 * @property string $make
 * @property string $model
 * @property int $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Trip[] $trips
 * @property-read int|null $trips_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Car newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Car newQuery()
 * @method static \Illuminate\Database\Query\Builder|Car onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Car query()
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereYear($value)
 * @method static \Illuminate\Database\Query\Builder|Car withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Car withoutTrashed()
 * @mixin \Eloquent
 * @property float $trip_miles
 * @method static \Illuminate\Database\Eloquent\Builder|Car whereTripMiles($value)
 */
class Car extends Model
{
    use SoftDeletes;

    protected $withCount = [
        'trips'
    ];

    protected $fillable = [
        'year',
        'make',
        'model',
        'trip_miles',
    ];


    protected static function booting()
    {
        static::deleting(function(Car $car){
            $car->trips()->delete();
            dispatch(new RecalculateTripTotals($car->user));
        });

        static::restored(function(Car $car){
            //restore all trips when it's restored
            Trip::withTrashed()->whereCarId($car->id)->restore();
            dispatch(new RecalculateTripTotals($car->user));
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function trips(){
        return $this->hasMany(Trip::class);
    }
}
