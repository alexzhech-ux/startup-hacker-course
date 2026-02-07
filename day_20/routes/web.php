<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\Admin\AdminUserController;

Route::middleware('auth')->group(function () {
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
});

Route::get('/', [BookController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/my-books', [BookController::class, 'myBooks']);

    Route::post('/books/{book}/rate', [BookController::class, 'rate']);

    Route::resource('books', BookController::class)
        ->only(['store', 'update', 'destroy']);
});


Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/registration', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('/login', fn () =>
    Inertia::render('Login')
)->name('login')->middleware('guest');


Route::middleware('auth')->group(function () {
    Route::get('/admin/books', [BookController::class, 'adminBooks']);
    Route::get('/admin/books/{user}', [BookController::class, 'adminBooksByUser']);
});


Route::get('/_debug-auth', function () {
    return response()->json([
        'auth_check' => Auth::check(),
        'user' => Auth::user(),
        'session_id' => session()->getId(),
    ]);
});

Route::middleware(['auth'])->group(function () {
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy']);
});