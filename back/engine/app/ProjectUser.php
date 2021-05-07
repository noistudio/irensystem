<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{

    public $timestamps = true;
    public $table = "proj_project_users";
    public $primaryKey = "last_id";
}