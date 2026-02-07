<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'booksite_book_genre',
            'genre_id',
            'book_id'
        );
    }
}