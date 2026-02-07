<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        return Inertia::render('HomeView', [
            'title' => 'Список книг',
            'books' => Book::with('genres')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['required', 'string'],
            'is_for_adult' => ['required', 'boolean'],
            'genres'       => ['array'],
            'cover'        => ['nullable', 'image', 'max:2048'],
            'rating_1'     => ['nullable', 'integer'],
            'rating_2'     => ['nullable', 'integer'],
            'rating_3'     => ['nullable', 'integer'],
            'rating_4'     => ['nullable', 'integer'],
            'rating_5'     => ['nullable', 'integer'],
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = '/storage/' . $path;
        }

        $data['user_id'] = auth()->id();

        $book = Book::create($data);

        $book->genres()->sync($data['genres'] ?? []);

        return back();
    }

    public function update(Request $request, Book $book)
{
    abort_unless($book->user_id === auth()->id(), 403);

    $data = $request->validate([
        'title'        => ['required', 'string', 'max:255'],
        'description'  => ['required', 'string'],
        'is_for_adult' => ['required', 'boolean'],
        'genres'       => ['array'],
        'genres.*'     => ['integer'],
        'cover'        => ['nullable', 'image', 'max:2048'],
    ]);

    if ($request->hasFile('cover')) {
        if ($book->cover) {
            Storage::disk('public')->delete(
                str_replace('/storage/', '', $book->cover)
            );
        }

        $data['cover'] = '/storage/' . $request
            ->file('cover')
            ->store('covers', 'public');
    }

    $book->update($data);
    $book->genres()->sync($data['genres'] ?? []);

    return back();
}

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        abort_unless($book->user_id === auth()->id(), 403);

        $book->delete();

        return back();
    }

    public function rate(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $rating = (int) $request->rating;

        abort_unless($rating >= 1 && $rating <= 5, 400);

        $book->increment('rating_' . $rating);

        return back();
    }

    public function myBooks()
    {
        $user = Auth::user();

        return Inertia::render('MyBooks', [
            'title' => 'Мои книги',
            'books' => Book::where('user_id', $user->id)
                ->with('genres')
                ->get(),
        ]);
    }

    public function adminBooks()
    {
        return Inertia::render('AdminList', [
            'title' => 'Администрирование книг',
            'users' => User::select('id', 'username')->get(),
            'selectedUser' => null,
            'books' => [],
        ]);
    }

    public function adminBooksByUser(User $user)
    {
        return Inertia::render('AdminList', [
            'title' => 'Администрирование книг',
            'users' => User::select('id', 'username')->get(),
            'selectedUser' => $user,
            'books' => Book::where('user_id', $user->id)
                ->with('genres')
                ->get(),
        ]);
    }
}