<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
});
// Books
Route::get('/books', function () {
    return view('pages.books.index');
})->name('books.index');

Route::get('/books/{isbn}', function ($isbn) {
    return view('pages.books.show', ['isbn' => $isbn]);
})->name('books.show');

// Authors
Route::get('/authors', function () {
    return view('pages.authors.index');
})->name('authors.index');

Route::get('/authors/{id}', function ($id) {
    return view('pages.authors.show', ['id' => $id]);
})->name('authors.show');

// Listas
Route::get('/lists', function () {
    return view('pages.lists.index');
})->name('lists.index');

// Usuarios
Route::get('/users', function () {
    return view('pages.users.index');
});

Route::get('/community', function () {
    return view('pages.users.community');
})->name('community.index');

// Login y registro
Route::get('/login', function () {
    return view('pages.users.login');
})->name('login');

Route::get('/register', function () {
    return view('pages.users.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('pages.users.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

// Espacio de administracion
// Admin Panel
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Books
    Route::get('/books', function () {
        return view('admin.books.index');
    })->name('admin.books.index');

    Route::get('/books/create', function () {
        return view('admin.books.edit');
    })->name('admin.books.create');

    Route::get('/books/{id}/edit', function ($id) {
        // Mock data usually passed here
        return view('admin.books.edit');
    })->name('admin.books.edit');

    // Authors
    Route::get('/authors', function () {
        return view('admin.authors.index');
    })->name('admin.authors.index');

    // Users
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('admin.users.index');

    // Moderation
    Route::get('/reviews', function () {
        return view('admin.reviews.moderation');
    })->name('admin.reviews.moderation');

    Route::get('/lists/reports', function () {
        return view('admin.lists.reports');
    })->name('admin.lists.reports');
});

// Static Pages
Route::get('/about', function () {
    return view('static.aboutus');
})->name('static.about');

Route::get('/contact', function () {
    return view('static.contact');
})->name('static.contact');

Route::get('/faq', function () {
    return view('static.faq');
})->name('static.faq');

Route::get('/privacy', function () {
    return view('pages.users.community');
})->name('community.index');
// Legal
Route::get('/privacy', function () {
    return view('static.privacy');
})->name('static.privacy');

Route::get('/terms', function () {
    return view('static.terms');
})->name('static.terms');

Route::get('/cookies', function (){
    return view('static.cookies');
})->name('static.cookies');



// User Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('pages.dashboard.index');
    })->name('dashboard.index');

    Route::get('/dashboard/profile', function () {
        return view('pages.dashboard.profile');
    })->name('dashboard.profile');

    Route::get('/dashboard/lists', function () {
        return view('pages.dashboard.lists');
    })->name('dashboard.lists');

    Route::get('/dashboard/reviews', function () {
        return view('pages.dashboard.reviews');
    })->name('dashboard.reviews');

    Route::get('/dashboard/settings', function () {
        return view('pages.dashboard.settings');
    })->name('dashboard.settings');
});
