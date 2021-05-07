<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = true;
    public $table = "proj_reviews";
    public $primaryKey = "last_id";

    public function who()
    {
        return $this->hasOne(User::class, "last_id", "who_send");
    }

}