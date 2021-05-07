<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    public $table = "proj_categorys";
    public $timestamps = true;
    public $primaryKey = "last_id";

}