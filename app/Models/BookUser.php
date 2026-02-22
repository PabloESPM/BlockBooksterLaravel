<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;
    protected $table = 'book_user';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'book_isbn',
        'fav_list_id',
        'status',
        'rating',
        'started_at',
        'finished_at'
    ];

    protected $dates = ['started_at', 'finished_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_isbn', 'isbn');
    }
}

