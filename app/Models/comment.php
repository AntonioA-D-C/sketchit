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
        return $this->belongsTo(post::class, 'post_ID', 'id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_ID', 'id');
    }
 
    public function replies(){
    //    return $this->hasMany(comment::class, 'parent_ID', 'id')->with("replies")->where("depth","<",4)->with("user")->with("likes");
    return $this->hasMany(comment::class, 'parent_ID', 'id')->with("user")->with("likes");
    }
    public function likes(){
        return $this->morphMany(Like::class, "likeable");
    }
    public function replies_multilevel(){
        return $this->hasMany(comment::class, 'parent_ID')->with('replies')->with('replies.likes')->with('replies.user');
    }
}
