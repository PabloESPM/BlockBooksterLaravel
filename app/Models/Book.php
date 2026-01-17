<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Book extends Model
{
    use HasFactory;
    protected $primaryKey = 'isbn';
    protected $keyType = 'string';
    public $incrementing = false;

    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            'author_book',
            'book_isbn',
            'author_id'
        )->withPivot(['role', 'author_order']);
    }
    // App/Models/Book.php
    public function lists()
    {
        return $this->belongsToMany(
            FavList::class,
            'book_list',
            'book_isbn',
            'list_id'
        )->withPivot(['position', 'note', 'added_at']);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_isbn', 'isbn');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'book_isbn', 'isbn');
    }
}

