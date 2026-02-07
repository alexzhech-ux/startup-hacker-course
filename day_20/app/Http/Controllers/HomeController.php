<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Book;
use Inertia\Inertia;

class Controller extends BaseController
{
    public function index()
    {
        $books = Book::with('genres', 'user')->get();

        return Inertia::render('HomeView', [
            'books' => $books,
            'count' => $books->count(),
            'title' => 'Список книг',
        ]);
    }
}
