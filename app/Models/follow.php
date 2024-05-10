<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    use HasFactory;

    public function followed(){
        return $this->hasMany(User::class, 'id', 'followed_id');
    }
    public function follower(){
        return $this->hasMany(User::class, 'id', 'follower_id');
    }
    protected static function boot(){
        parent::boot();

        static::saving(function($follow){
            if($follow->follower_id === $follow->followed_id){
                throw new \Exception("Can't follow yourself");
            }
        });
    }
}
