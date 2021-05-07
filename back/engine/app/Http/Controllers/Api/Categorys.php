<?php


namespace App\Http\Controllers\Api;


use App\Category;
use App\Http\Controllers\Controller;

class Categorys extends Controller
{


    function all(){
        $categorys=Category::all();
        return $categorys;
    }
}