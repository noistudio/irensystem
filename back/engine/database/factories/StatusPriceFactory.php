<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'enable'=>1,
        'title'=>'Статус цена '.rand(0,100),
        'issearch'=>0,
        'isprice'=>1,
        'iswork'=>0,
        'isfinish'=>0
        //
    ];
});
