<?php


namespace App\Http\Controllers\Api;


use App\Category;
use App\Http\Controllers\Controller;
use App\Page;
use App\Post;
use App\User;

class About extends Controller
{

    public function index()
    {

        $about = \App\About::query()->where("enable", 1)->first();

        $categorys = Category::query()->where("enable", 1)->get();

        $posts = Post::query()->with("category_post")->where(
            function ($query) {
                $query->where("enable", 1);
                $query->whereHas(
                    "category_post",
                    function ($query_cat) {
                        $query_cat->where("enable", 1);
                        $query_cat->where("isprivate", 0);
                        $query_cat->where("ismain", 1);
                    }
                );
            }
        )->orderByDesc("created_at")->limit(6)->get();

        $teams = User::query()->where("enable", 1)->where("isdeveloper", 1)->where("isteam", 1)->get();

        $portfolio = \App\Portfolio::query()->with('category', 'user')->orderByDesc("date_end")->get();

        return array(
            'type' => 'success',
            'works' => $portfolio,
            'posts' => $posts,
            'teams' => $teams,
            'about' => $about,
            'categorys' => $categorys,
        );
    }


}