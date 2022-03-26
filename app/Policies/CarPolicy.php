<?php

namespace App\Policies;

use App\Car;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Car $car){
        return $user->id == $car->user_id;
    }

    public function delete(User $user, Car $car){
        return $user->id == $car->user_id;
    }

}
