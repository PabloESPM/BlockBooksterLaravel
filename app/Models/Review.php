<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_isbn', 'isbn');
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }
}

