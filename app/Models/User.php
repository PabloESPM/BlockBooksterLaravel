<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $hidden = ['password'];

    /* Relaciones */

    public function lists()
    {
        return $this->hasMany(FavList::class, 'user_email', 'email');
    }

    public function books()
    {
        return $this->hasMany(BookUser::class, 'user_email', 'email');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_email', 'email');
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_email', 'email');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_email', 'email');
    }
}

