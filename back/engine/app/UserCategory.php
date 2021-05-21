<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class UserCategory extends Model
{
    //
    public $table = "proj_users_categorys";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function category()
    {
        return $this->hasOne(Category::class, "last_id", "category_id");
    }
    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

}