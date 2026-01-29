<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('HomeView', [
        'count' => 5,
        'title' => 'Список книг',
        'books' => ['1984', 'Dune', 'Foundation'],
        'author' => [
            'name' => 'Isaac Asimov',
            'country' => 'USA',
            'alive' => false,
        ],
    ]);
});

Route::get('/about-us', fn () =>
    Inertia::render('AboutUs')
);

Route::get('/dashboard', fn () =>
    Inertia::render('Dashboard')
);

Route::get('/profile', fn () =>
    Inertia::render('Profile')
);

Route::post('/dashboard', function (Request $request) {
    return back();
});