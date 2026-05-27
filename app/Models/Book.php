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

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    // Relación con los usuarios a través de la tabla pivote book_user
    // Esto permite acceder a las valoraciones (rating) que los usuarios han dado al libro
    public function users()
    {
        return $this->belongsToMany(User::class, 'book_user', 'book_isbn', 'user_id')
            ->withPivot(['rating', 'status', 'started_at', 'finished_at']);
    }

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

    /**
     * Accessor para obtener la URL de la portada del libro.
     * Si el cover_path existe, devuelve la URL de Storage. En caso contrario, null.
     */
    public function getCoverImageAttribute(): ?string
    {
        if ($this->cover_path) {
            return \Illuminate\Support\Facades\Storage::url($this->cover_path);
        }

        return null;
    }

    /**
     * Accessor de compatibilidad para obtener la URL de la portada del libro.
     *
     * @return string|null
     */
    public function getCoverAttribute(): ?string
    {
        return $this->cover_image;
    }
}


