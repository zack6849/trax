<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //our user
        factory(\App\User::class, 1)->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        //randos
        factory(\App\User::class, 10)->create([
            'password' => Hash::make('password'),
        ]);
    }
}
