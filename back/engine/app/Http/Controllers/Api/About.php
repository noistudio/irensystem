<?php


namespace App\Http\Controllers\Api;


use App\Category;
use App\Http\Controllers\Controller;
use App\User;

class About extends Controller
{

    public function index()
    {

        $about = \App\About::query()->where("enable", 1)->first();

        $categorys = Category::query()->where("enable", 1)->get();

        $teams = User::query()->where("enable", 1)->where("isdeveloper", 1)->get();

        $portfolio = \App\Portfolio::query()->with('category', 'user')->orderByDesc("date_end")->get();

        return array('type' => 'success', 'works' => $portfolio, 'teams' => $teams, 'about' => $about, 'categorys' => $categorys);
    }
}