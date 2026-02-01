<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle global search across multiple models.
     */
    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        if (empty($query)) {
            return redirect()->route('home')->with('error', 'Please enter a search term');
        }

        // Search Books (title, ISBN)
        $books = \App\Models\Book::where('title', 'ILIKE', "%{$query}%")
            ->orWhere('isbn', 'ILIKE', "%{$query}%")
            ->with('authors')
            ->limit(10)
            ->get();

        // Search Authors
        $authors = \App\Models\Author::where('name', 'ILIKE', "%{$query}%")
            ->withCount('books')
            ->limit(10)
            ->get();

        // Search Users (public profiles only)
        $users = \App\Models\User::where('name', 'ILIKE', "%{$query}%")
            ->where('profile_visibility', 'public')
            ->withCount('followers')
            ->limit(10)
            ->get();

        // Search Lists (public only)
        $lists = \App\Models\FavList::where('name', 'ILIKE', "%{$query}%")
            ->where('is_public', true)
            ->with('user')
            ->withCount('books')
            ->limit(10)
            ->get();

        // Search Genres
        $genres = \App\Models\Genre::where('name', 'ILIKE', "%{$query}%")
            ->limit(10)
            ->get();

        $totalResults = $books->count() + $authors->count() + $users->count() + $lists->count() + $genres->count();

        return view('pages.search.results', compact('query', 'books', 'authors', 'users', 'lists', 'genres', 'totalResults'));
    }
}
