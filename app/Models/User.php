<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable=[
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'user_name'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(){
      return  $this->hasMany(post::class);
    }
    public function comments(){
        return $this->hasMany(comment::class);
    }
    /*
    public function notifications(){
        return $this->hasMany(notification::class);
    }*/
    public function verification_codes(){
        return $this->hasMany(emailverificationcodes::class);
    }
    public function followers(){
        return  $this->hasMany(follow::class, 'followed_id', 'id');
      }
      public function followed(){
        return  $this->hasMany(follow::class,'follower_id', 'id'  );
      }

      
    
}
