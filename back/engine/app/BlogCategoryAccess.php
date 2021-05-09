<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategoryAccess extends Model
{
    //
    public $table = "blog_categorys_access";
    public $timestamps = true;
    public $primaryKey = "last_id";


    public function category()
    {
        return $this->hasOne(BlogCategory::class, "last_id", "category_id");
    }

}