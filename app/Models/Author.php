<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;
    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'author_book',
            'author_id',
            'book_isbn'
        )->withPivot(['role', 'author_order']);
    }
}

