<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BlogCategory extends Model
{
    //
    public $table = "blog_categorys";
    public $timestamps = true;
    public $primaryKey = "last_id";


    public function my_access()
    {
        if (request()->user()) {
            return $this->hasOne(BlogCategoryAccess::class, "category_id", "last_id")->where(
                "user_id",
                request()->user()->last_id
            );
        }else {
            return $this->hasOne(BlogCategoryAccess::class, "category_id", "last_id")->where(
                "user_id",
                0
            );
        }


    }

}