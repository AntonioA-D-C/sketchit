<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class emailverificationcodes extends Model
{

    function user(){
        return $this->belongsTo(User::class);
    }
    use HasFactory;

    protected $guarded = [];
}
