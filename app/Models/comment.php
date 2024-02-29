<?php

namespace App\Models;

use App\Models\Like;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class comment extends Model
{
    use HasFactory;

    protected $guarded =[];
    public function get_post(){
        return $this->hasOne(post::class);
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }
    public function commentable(){
        return $this->morphTo();
    }
    public function replies()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function likes(){
        return $this->morphMany(Like::class, "likeable");
    }
}
