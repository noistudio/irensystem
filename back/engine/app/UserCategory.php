<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    //
    public $table = "proj_users_categorys";
    public $timestamps = true;
    public $primaryKey = "last_id";

}