<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $casts = [
        'json' => 'json',
    ];
    public $table = "proj_portfolio";
    public $timestamps = true;
    public $primaryKey = "last_id";

    public function user()
    {
        return $this->hasOne(User::class, "last_id", "user_id");
    }

    public function category()
    {
        return $this->hasOne(PortfolioCategory::class, "last_id", "category_id");
    }
}