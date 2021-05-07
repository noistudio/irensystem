<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\User;


class Developer extends Controller
{

    public function getWork($username, $work_id)
    {
        $user = User::query()->where("username", $username)->where("isdeveloper", 1)->first();
        if (!$user) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }
        $portfolio = \App\Portfolio::query()->with('category')->where("user_id", $user->last_id)->where("last_id", $work_id)->first();
        if (!$portfolio) {
            return array('type' => 'error', 'message' => 'Документ не найден!');
        }
        return array('type' => 'success', 'developer' => $user, 'work' => $portfolio);
    }

    public function reviews($username)
    {
        $user = User::query()->with("reviews", "reviews.who")->where("username", $username)->where("isdeveloper", 1)->first();
        if (!$user) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }

        return array('type' => 'success', 'developer' => $user);
    }

    public function get($username)
    {
        $user = User::query()->with("portfolio", "portfolio.category")->where("username", $username)->where("isdeveloper", 1)->first();
        if (!$user) {
            return array('type' => 'error', 'message' => 'Пользователь не найден!');
        }

        return array('type' => 'success', 'developer' => $user);

    }

}