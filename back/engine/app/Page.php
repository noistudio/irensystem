<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    public $table = "pages";
    public $timestamps = true;
    public $primaryKey = "last_id";

   
}