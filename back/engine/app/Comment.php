<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $table = "proj_project_comments";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

    public function comments()
    {
        return $this->hasMany(Subcomment::class, "comment_id", "last_id");
    }
}