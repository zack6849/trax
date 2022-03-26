<?php

namespace App\Jobs;

use App\Trip;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * This wasn't really in the spec, and depending on the actual use case, this may well be the opposite of what you want
 *
 * The idea here was, if you for some reason deleted a car, the totals would recalculate and re-total themselves
 *
 * I'd originally gone for an approach that relied upon calculating the totals on the fly, but that was an n+1 query issue
 * I couldn't find a simple (and clean) way to include the sum of a relation's column eagerly to minimize queries, so I didn't.
 *
 */
class RecalculateTripTotals implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //reset all totals to zero
        $this->user->trips()->withTrashed()->update(['total' => 0]);
        // opposite of normal order, since we need to count up from the earliest trip.
        $trips_in_order = $this->user->trips()->orderBy('date')->orderBy('id')->get();
        $running_total = 0;
        /** @var Trip $trip */
        foreach ($trips_in_order as $trip){
            $running_total = bcadd($running_total, $trip->miles, 1);
            $trip->total = $running_total;
            $trip->save();
        }
    }
}
