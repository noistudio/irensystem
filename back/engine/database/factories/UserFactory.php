<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(
    User::class,
    function (Faker $faker) {
        return [
            'enable' => 1,
            'name' => Str::random(10),
            'account' => Str::random(10),
            "avatar" => '/files/tmpfiles/default-avatar.png',
            'username' => Str::random(10),
            'telegram_id' => rand(0, 1000000),
            'isdeveloper' => 1,
            'api_token' => Str::random(10),
            'isteam' => 1,
            'job' => 'Testing',

        ];
    }
);
