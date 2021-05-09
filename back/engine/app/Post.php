<?php


namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    //
    protected $casts = [
        'content' => 'json',
        'short' => 'json',
    ];
    public $table = "blog_posts";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

    public function category_post()
    {
        return $this->hasOne(BlogCategory::class, "last_id", "category");
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, "post_id", "last_id");
    }

    public function my_access()
    {
        if (Auth::guard("api")->check()) {
            $user = Auth::guard("api")->user();

            return $this->hasOne(BlogCategoryAccess::class, "category_id", "category")->where(
                "user_id",
                $user->last_id
            );
        } else {
            return $this->hasOne(BlogCategoryAccess::class, "category_id", "category")->where(
                "user_id",
                0
            );
        }


    }

}