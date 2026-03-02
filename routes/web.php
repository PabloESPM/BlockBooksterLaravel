<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Search
use App\Http\Controllers\SearchController;
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Books
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Authors
use App\Http\Controllers\AuthorController;
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/authors/{author}/books', [AuthorController::class, 'books'])->name('authors.books');

// Listas
// Listas
use App\Http\Controllers\FavListController;
Route::get('/lists', [FavListController::class, 'index'])->name('lists.index');
Route::get('/lists/{list}', [FavListController::class, 'show'])->name('lists.show');

// Usuarios
use App\Http\Controllers\UserProfileController;
Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');

// Follow / Unfollow (auth protected)
use App\Http\Controllers\FollowController;
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'toggleUser'])->name('users.follow');
    Route::post('/authors/{author}/follow', [FollowController::class, 'toggleAuthor'])->name('authors.follow');
    Route::post('/lists/{list}/follow', [FollowController::class, 'toggleList'])->name('lists.follow');
});

Route::get('/community', [UserController::class, 'community'])->name('community.index');

// Login y registro
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'authenticate']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::get('/forgot-password', function () {
    return view('pages.users.forgot-password');
})->name('password.request');

// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//    ->name('password.email');

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

Route::get('/cookies', function () {
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

    Route::get('/dashboard/lists', [FavListController::class, 'dashboardIndex'])->name('dashboard.lists');
    Route::post('/dashboard/lists', [FavListController::class, 'store'])->name('dashboard.lists.store');
    Route::delete('/dashboard/lists/{list}', [FavListController::class, 'destroy'])->name('dashboard.lists.destroy');
    Route::put('/dashboard/lists/{list}', [FavListController::class, 'update'])->name('dashboard.lists.update');
    Route::post('/dashboard/lists/{list}/attach', [FavListController::class, 'attachBook'])->name('dashboard.lists.attach');
    Route::post('/dashboard/lists/create-attach', [FavListController::class, 'storeAndAttach'])->name('dashboard.lists.storeAndAttach');

    Route::get('/dashboard/reviews', [ReviewController::class, 'dashboardIndex'])->name('dashboard.reviews');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/toggle-like', [ReviewController::class, 'toggleLike'])->name('reviews.toggle-like');

    Route::get('/dashboard/settings', function () {
        return view('pages.dashboard.settings');
    })->name('dashboard.settings');
});
