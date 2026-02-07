<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;

Route::middleware(['web'])->group(function () {

    Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
    Route::post('/registration', [AuthController::class, 'register'])->middleware('guest');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

    Route::get('/', fn () =>
        Inertia::render('HomeView', [
            'count' => 5,
            'title' => 'Список книг',
            'books' => ['1984', 'Dune', 'Foundation'],
        ])
    );

    Route::get('/about-us', fn () =>
        Inertia::render('AboutUs')
    );

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', fn () => Inertia::render('Dashboard'));
        Route::get('/profile', fn () => Inertia::render('Profile'));
        Route::get('/my-books', fn () => Inertia::render('MyBooks'));
    });

});