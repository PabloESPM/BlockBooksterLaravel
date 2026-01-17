<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;
    protected $table = 'book_user';
    public $timestamps = false;

    protected $dates = ['started_at', 'finished_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_isbn', 'isbn');
    }
}

