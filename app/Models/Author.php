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
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Users following this author.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'author_followers', 'author_id', 'user_id')->withTimestamps();
    }

    /**
     * Check if this author is followed by a user.
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('user_id', $user->id)->exists();
    }
}

