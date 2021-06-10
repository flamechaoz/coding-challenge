<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = "tbl_staff";

    public function user(){
        return $this->hasOne(\App\User::class);
    }
}
