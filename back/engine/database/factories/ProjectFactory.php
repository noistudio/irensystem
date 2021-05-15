<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name_project'=>'Project 1',
        'category_id'=>factory(\App\Category::class),
        'isclose'=>0,

        //
    ];
});
