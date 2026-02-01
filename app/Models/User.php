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


    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'date_of_birth',
        'gender',
        'country_id',
        'type',
        'avatar',
        'profile_visibility'
    ];

    protected $hidden = ['password', 'remember_token'];

    /* Relaciones */

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function lists()
    {
        return $this->hasMany(FavList::class);
    }

    public function books()
    {
        return $this->hasMany(BookUser::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followed_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    /**
     * Check if this user is friends with another user (mutual follow).
     */
    public function isFriend(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists() &&
            $this->followers()->where('follower_id', $user->id)->exists();
    }

    /**
     * Check if this user is following another user.
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }
}

