<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Genre;

class Book extends Model
{
    protected $table = 'booksite_books';

    protected $fillable = [
        'title',
        'description',
        'cover',
        'is_for_adult',
        'rating_1',
        'rating_2',
        'rating_3',
        'rating_4',
        'rating_5',
        'user_id',
    ];

    protected $casts = [
        'is_for_adult' => 'boolean',
        'rating_1'     => 'integer',
        'rating_2'     => 'integer',
        'rating_3'     => 'integer',
        'rating_4'     => 'integer',
        'rating_5'     => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function genres()
    {
        return $this->belongsToMany(
            Genre::class,
            'booksite_book_genre',
            'book_id',
            'genre_id'
        );
    }

    public function getRatingAttribute(): float
    {
        $ratings = [
            1 => $this->rating_1,
            2 => $this->rating_2,
            3 => $this->rating_3,
            4 => $this->rating_4,
            5 => $this->rating_5,
        ];

        $totalVotes = array_sum($ratings);

        if ($totalVotes === 0) {
            return 0;
        }

        $sum = 0;
        foreach ($ratings as $star => $count) {
            $sum += $star * $count;
        }

        return round($sum / $totalVotes, 2);
    }
}