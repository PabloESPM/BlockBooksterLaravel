<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_isbn',
        'title',
        'body'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_isbn', 'isbn');
    }

    public function likes()
    {
        return $this->hasMany(ReviewLike::class);
    }

    public function getRatingAttribute()
    {
        $bookUser = \App\Models\BookUser::where('user_id', $this->user_id)
            ->where('book_isbn', $this->book_isbn)
            ->first();

        return $bookUser ? $bookUser->rating : 0;
    }
}

