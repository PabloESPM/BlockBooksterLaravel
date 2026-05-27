<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;

    /**
     * Campos permitidos para asignación masiva (Author::create / Author::fill)
     * según la migración: 09_create_authors_table.php
     */
    protected $fillable = [
        'name',
        'surname',
        'birth_date',
        'country_id',
        'biography',
        'photo_url',
    ];

    /**
     * Accessor 'photo' → devuelve la URL de la foto del autor o el avatar por defecto.
     *
     * Resuelve de forma unificada si la URL es externa (http/https) o local (Storage::url),
     * y genera el fallback a ui-avatars si no hay foto configurada.
     */
    protected function photo(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->photo_url) {
                    $fullName = trim(($this->name ?? '') . ' ' . ($this->surname ?? ''));
                    return 'https://ui-avatars.com/api/?name=' . urlencode($fullName ?: 'A') . '&background=random&size=256';
                }

                if (str_starts_with($this->photo_url, 'http://') || str_starts_with($this->photo_url, 'https://')) {
                    return $this->photo_url;
                }

                return \Illuminate\Support\Facades\Storage::url($this->photo_url);
            }
        );
    }

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
     * Usuario sigue a este autor.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'author_followers', 'author_id', 'user_id')->withTimestamps();
    }

    /**
     * Chequea si el autor esta seguido por usuarios.
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('user_id', $user->id)->exists();
    }
}

