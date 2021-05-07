<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ProjectOffer extends Model
{

    public $timestamps = true;
    public $table = "proj_project_offers";
    public $primaryKey = "last_id";

    public function developer()
    {
        return $this->hasOne(User::class, "last_id", "developer_id");
    }

    public function comments()
    {
        return $this->hasMany(OfferComment::class, "offer_id", "last_id");
    }
}