<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /** @var User $user */
    public $user;
    /** @var User $other_user */
    public $other_user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        factory(User::class, 10)->create();
        //grab another random user as our "other user".
        $this->other_user = User::where('id', '!=', $this->user->id)->inRandomOrder()->first();
    }
}
