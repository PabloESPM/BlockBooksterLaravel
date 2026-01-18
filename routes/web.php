<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});

Route::get('/books', function () {
    return view('pages.books.index');
});

Route::get('/books/project-hail-mary', function () {
    return view('pages.books.show');
});

Route::get('/authors', function () {
    return view('pages.authors.index');
});

Route::get('/lists', function () {
    return view('pages.lists.index');
});

Route::get('/users', function () {
    return view('pages.users.index');
});

// Auth placeholders for UI testing
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/admin', function () {
    return view('admin.dashboard'); // make sure this exists or point to a placeholder
});


