<?php


namespace App\Http\Controllers\Api;


use App\Category;
use App\Http\Controllers\Controller;
use App\Page;
use App\User;

class Pages extends Controller
{


    function all()
    {

        $pages = Page::query()->where("enable", 1)->orderBy("sort")->get();

        return $pages;
    }

    function show($id)
    {

        $page = Page::query()->where("enable", 1)->where("last_id", $id)->first();
        if ($page) {
            return array('type' => 'success', 'page' => $page);
        } else {
            return array('type' => 'error');
        }


    }
}