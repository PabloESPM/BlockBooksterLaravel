<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone_code',
        'iso_code',
        'currency',
        'continent',
        'timezone'
    ];

    public function authors()
    {
        return $this->hasMany(Author::class);
    }
}
