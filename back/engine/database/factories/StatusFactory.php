<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Status;
use Faker\Generator as Faker;

$factory->define(Status::class, function (Faker $faker) {
    return [
        'enable'=>1,
        'title'=>'Статус поиск '.rand(0,100),
        'issearch'=>1,
        'isprice'=>0,
        'iswork'=>0,
        'isfinish'=>0
        //
    ];
});
