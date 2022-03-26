<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            //these may benefit from being actual entries in a table later
            //would be neat for analytics for things like finding out how many miles all fords have driven or something
            //but the frontend seems to assume it'd be a text input, so i'm not going to bother
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->float('trip_miles')->default(0);
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
        Schema::dropIfExists('cars');
    }
}
