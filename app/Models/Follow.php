<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Follow extends Model
{
    use HasFactory;
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_email', 'email');
    }

    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_email', 'email');
    }
}

