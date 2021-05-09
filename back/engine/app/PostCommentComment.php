<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class PostCommentComment extends Model
{
    public $table = "blog_posts_comments_comments";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }




}