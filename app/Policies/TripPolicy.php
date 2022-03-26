<?php

namespace App\Policies;

use App\Trip;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TripPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Trip $trip){
        return $user->id == $trip->user_id;
    }

    public function delete(User $user, Trip $trip){
        return $user->id == $trip->user_id;
    }
}
