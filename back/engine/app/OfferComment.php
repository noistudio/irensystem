<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class OfferComment extends Model
{

    public $timestamps = true;
    public $table = "proj_project_offers_comments";
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

}