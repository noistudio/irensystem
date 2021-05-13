<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserCategory;
use Faker\Generator as Faker;

$factory->define(
    UserCategory::class,
    function (Faker $faker) {
        return [
            'user_id' => factory(App\User::class),
            'category_id' => factory(\App\Category::class),
            'enable' => 1,
            'start_time'=>date('Y-m-d H:i:s'),
            'developer_id'=>factory(App\User::class),
            'client_id'=>factory(App\User::class),
            'status'=>factory(App\Status::class),
            'json'=>"{}",
            'main_project_id'=>0,

            //
        ];
    }
);
