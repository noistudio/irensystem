<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    public $table = "about";
    public $timestamps = true;
    public $primaryKey = "last_id";
}