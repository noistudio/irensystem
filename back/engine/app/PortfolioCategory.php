<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class PortfolioCategory extends Model
{

    public $table = "proj_portfolio_categorys";
    public $timestamps = true;
    public $primaryKey = "last_id";
}