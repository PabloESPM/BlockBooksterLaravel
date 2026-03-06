<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Maneja la búsqueda global a través de múltiples modelos.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        if (empty($query)) {
            return redirect()->route('home')->with('error', 'Por favor, ingresa un término de búsqueda');
        }

        // Buscar Libros (título, ISBN)
        $books = \App\Models\Book::where('title', 'ILIKE', "%{$query}%")
            ->orWhere('isbn', 'ILIKE', "%{$query}%")
            ->with('authors')
            ->limit(10)
            ->get();

        // Buscar Autores
        $authors = \App\Models\Author::where('name', 'ILIKE', "%{$query}%")
            ->withCount('books')
            ->limit(10)
            ->get();

        // Buscar Usuarios (solo perfiles públicos)
        $users = \App\Models\User::where('name', 'ILIKE', "%{$query}%")
            ->where('profile_visibility', 'public')
            ->withCount('followers')
            ->limit(10)
            ->get();

        // Buscar Listas (solo públicas)
        $lists = \App\Models\FavList::where('name', 'ILIKE', "%{$query}%")
            ->where('is_public', true)
            ->with('user')
            ->withCount('books')
            ->limit(10)
            ->get();

        // Buscar Géneros
        $genres = \App\Models\Genre::where('name', 'ILIKE', "%{$query}%")
            ->limit(10)
            ->get();

        $totalResults = $books->count() + $authors->count() + $users->count() + $lists->count() + $genres->count();

        return view('pages.search.results', compact('query', 'books', 'authors', 'users', 'lists', 'genres', 'totalResults'));
    }
}

