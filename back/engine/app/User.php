<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    protected $table = "proj_users";
    protected $primaryKey = "last_id";
    public $timestamps = true;

    protected $hidden = [
        'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    public function portfolio()
    {
        return $this->hasMany(Portfolio::class, "user_id", "last_id");
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, "user_id", "last_id");
    }
}
