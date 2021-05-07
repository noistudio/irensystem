<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public $timestamps = true;
    public $table = "proj_invoices";
    public $primaryKey = "last_id";

    public function client()
    {
        return $this->hasOne(User::class, "last_id", "client_id");
    }

    public function developer()
    {
        return $this->hasOne(User::class, "last_id", "developer_id");
    }
}