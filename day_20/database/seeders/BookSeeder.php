<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('booksite_users')
            ->where('username', 'Admin')
            ->value('id');

        $books = [
            [
                'title' => 'Старик и море',
                'description' => 'Повесть о старом рыбаке.',
                'cover' => '/img/1.png',
                'is_for_adult' => false,
                'user_id' => $adminId,
                'ratings' => [0, 0, 0, 0, 0],
                'genres' => ['Повесть'],
            ],
            [
                'title' => 'Камасутра',
                'description' => 'Собрание поз.',
                'cover' => '/img/2.png',
                'is_for_adult' => true,
                'user_id' => $adminId,
                'ratings' => [0, 0, 0, 1, 0],
                'genres' => ['Эротика'],
            ],
            [
                'title' => 'Сильмариллион',
                'description' => 'Фэнтези о богах.',
                'cover' => '/img/3.png',
                'is_for_adult' => false,
                'user_id' => $adminId,
                'ratings' => [1, 0, 0, 0, 0],
                'genres' => ['Фэнтези'],
            ],
            [
                'title' => 'Краткая история времени',
                'description' => 'О вселенной.',
                'cover' => '/img/4.png',
                'is_for_adult' => false,
                'user_id' => $adminId,
                'ratings' => [0, 0, 1, 0, 0],
                'genres' => ['Эссе'],
            ],
            [
                'title' => 'Искусство войны',
                'description' => 'Древний трактат.',
                'cover' => '/img/5.png',
                'is_for_adult' => false,
                'user_id' => $adminId,
                'ratings' => [0, 1, 0, 0, 0],
                'genres' => ['Эссе'],
            ],
        ];

        foreach ($books as $book) {
            $bookId = DB::table('booksite_books')->insertGetId([
                'title' => $book['title'],
                'description' => $book['description'],
                'cover' => $book['cover'],
                'is_for_adult' => $book['is_for_adult'],
                'user_id' => $book['user_id'],
                'rating_1' => $book['ratings'][0],
                'rating_2' => $book['ratings'][1],
                'rating_3' => $book['ratings'][2],
                'rating_4' => $book['ratings'][3],
                'rating_5' => $book['ratings'][4],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($book['genres'] as $genreName) {
                $genreId = DB::table('genres')
                    ->where('name', $genreName)
                    ->value('id');

                DB::table('booksite_book_genre')->insert([
                    'book_id' => $bookId,
                    'genre_id' => $genreId,
                ]);
            }
        }
    }
}