<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    public $table = "blog_posts_comments";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

    public function comments()
    {
        return $this->hasMany(PostCommentComment::class, "comment_id", "last_id");
    }

}