<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->references('id')->on('cars');
            //denormalize for efficiency in grabbing all trips for a user.
            $table->foreignId('user_id')->references('id')->on('users');
            $table->date('date');
            //considered using unsigned since miles wouldn't be negative (likely), but the chances of needing it for a trip is basically zero
            $table->float('miles');
            $table->float('total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
}
