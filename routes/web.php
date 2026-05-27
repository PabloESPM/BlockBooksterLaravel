<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\BookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HomeController;

Route::livewire('/', 'pages.home.index')->name('home');

// Buscador — migrado a Livewire SFC
use App\Http\Controllers\SearchController;

Route::livewire('/search', 'pages.search.results')->name('search');

// Libros
Route::livewire('/books', 'pages.books.index')->name('books.index');
// Página de detalle migrada a Livewire SFC — eliminada la ruta AJAX de carga de reseñas
Route::livewire('/books/{book}', 'pages.books.show')->name('books.show');

// Autores
Route::livewire('/authors', 'pages.authors.index')->name('authors.index');
Route::livewire('/authors/{author}', 'pages.authors.show')->name('authors.show');
// La ruta authors.books ya no es necesaria pues el componente Livewire maneja la carga incremental

// Listas
use App\Http\Controllers\FavListController;

Route::livewire('/lists', 'pages.list.index')->name('lists.index');
Route::livewire('/lists/{list}', 'pages.list.show')->name('lists.show');

// Usuarios
use App\Http\Controllers\UserProfileController;

Route::livewire('/users/{user}', 'pages.users.show')->name('users.show');

// Follow / Unfollow (auth protected)
use App\Http\Controllers\FollowController;

Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class , 'toggleUser'])->name('users.follow');
    Route::post('/authors/{author}/follow', [FollowController::class , 'toggleAuthor'])->name('authors.follow');
    Route::post('/lists/{list}/follow', [FollowController::class , 'toggleList'])->name('lists.follow');
});

// Comunidad
Route::livewire('/community', 'pages.users.index')->name('community.index');

// Login y registro
Route::livewire('/login', 'pages.auth.login')->name('login');
Route::livewire('/register', 'pages.auth.register')->name('register');
Route::livewire('/forgot-password', 'pages.auth.forgot-password')->name('password.request');

// Logout (mantenemos el controller porque es una acción POST simple y no requiere vista)
Route::post('/logout', [UserController::class , 'logout'])->name('logout');


// Espacio de administracion
// Panel Administracion
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::livewire('/', 'admin.dashboard')->name('admin.dashboard');

    // Libros
    Route::livewire('/books', 'admin.books.index')->name('admin.books.index');

    Route::livewire('/books/create', 'admin.books.edit')->name('admin.books.create');
    Route::livewire('/books/{id}/edit', 'admin.books.edit')->name('admin.books.edit');

    // Autores
    Route::livewire('/authors', 'admin.authors.index')->name('admin.authors.index');
    Route::livewire('/authors/create', 'admin.authors.edit')->name('admin.authors.create');
    Route::livewire('/authors/{id}/edit', 'admin.authors.edit')->name('admin.authors.edit');

    // Usuarios
    Route::livewire('/users', 'admin.users.index')->name('admin.users.index');
    Route::livewire('/users/{user}', 'admin.users.show')->name('admin.users.show');

    Route::livewire('/reviews', 'admin.reviews.moderation')->name('admin.reviews.moderation');

    Route::livewire('/lists/reports', 'admin.lists.reports')->name('admin.lists.reports');
});

// Paginas Estaticas
Route::get('/about', function () {
    return view('static.aboutus');
})->name('static.about');

Route::get('/contact', function () {
    return view('static.contact');
})->name('static.contact');

Route::get('/faq', function () {
    return view('static.faq');
})->name('static.faq');


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


use App\Http\Controllers\DashboardController;

// Panel administración del Usuario — páginas GET migradas a Livewire SFC
Route::livewire('/dashboard', 'pages.dashboard.index')->name('dashboard.index')->middleware('auth');
Route::livewire('/dashboard/profile', 'pages.dashboard.profile')->name('dashboard.profile')->middleware('auth');
Route::livewire('/dashboard/lists', 'pages.dashboard.lists')->name('dashboard.lists')->middleware('auth');
Route::livewire('/dashboard/reviews', 'pages.dashboard.reviews')->name('dashboard.reviews')->middleware('auth');
Route::livewire('/dashboard/social', 'pages.dashboard.social')->name('dashboard.social')->middleware('auth');
Route::livewire('/dashboard/settings', 'pages.dashboard.settings')->name('dashboard.settings')->middleware('auth');

// Acciones POST/PUT/DELETE del dashboard (controladores clásicos)
Route::middleware(['auth'])->group(function () {
    Route::put('/dashboard/profile', [DashboardController::class , 'updateProfile'])->name('dashboard.profile.update');

    Route::post('/dashboard/lists', [FavListController::class , 'store'])->name('dashboard.lists.store');
    Route::delete('/dashboard/lists/{list}', [FavListController::class , 'destroy'])->name('dashboard.lists.destroy');
    Route::put('/dashboard/lists/{list}', [FavListController::class , 'update'])->name('dashboard.lists.update');
    Route::post('/dashboard/lists/{list}/attach', [FavListController::class , 'attachBook'])->name('dashboard.lists.attach');
    Route::post('/dashboard/lists/create-attach', [FavListController::class , 'storeAndAttach'])->name('dashboard.lists.storeAndAttach');
    Route::post('/dashboard/lists/{list}/toggle-like', [FavListController::class , 'toggleLike'])->name('dashboard.lists.toggle-like');

    Route::post('/reviews', [ReviewController::class , 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class , 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class , 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/{review}/toggle-like', [ReviewController::class , 'toggleLike'])->name('reviews.toggle-like');

    Route::put('/dashboard/settings', [DashboardController::class , 'updateSettings'])->name('dashboard.settings.update');
    Route::put('/dashboard/settings/privacy', [DashboardController::class , 'updatePrivacy'])->name('dashboard.settings.privacy');
    Route::delete('/dashboard/settings/destroy', [DashboardController::class , 'destroyAccount'])->name('dashboard.settings.destroy');
});