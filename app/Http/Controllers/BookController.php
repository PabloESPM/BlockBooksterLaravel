<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Book::with('authors');

        // Search (Title/Author/ISBN) from Advanced Search or simple search
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->filled('author')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->author . '%');
            });
        }
        if ($request->filled('isbn')) {
            $query->where('isbn', 'like', '%' . $request->isbn . '%');
        }

        // Sidebar Filters
        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('language')) {
            $query->whereHas('language', function ($q) use ($request) {
                $q->where('code', $request->language);
            });
        }


        if ($request->filled('country')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('country_id', $request->country);
            });
        }

        if ($request->filled('pages_from')) {
            $query->where('number_of_pages', '>=', $request->pages_from);
        }
        if ($request->filled('pages_to')) {
            $query->where('number_of_pages', '<=', $request->pages_to);
        }

        if ($request->filled('year_from')) {
            $query->where('publication_year', '>=', $request->year_from);
        }
        if ($request->filled('year_to')) {
            $query->where('publication_year', '<=', $request->year_to);
        }

        // Rating (Mock logic since we don't have a computed rating column yet, or assuming we might)
        // For now, if we had a rating column: $query->where('average_rating', '>=', $request->rating);
        // I will omit strict rating filtering logic on DB if column doesn't exist, 
        // effectively ignoring it to prevent SQL errors, 
        // OR assuming there IS a column/relation. 
        // Based on `Book` model viewing earlier, I didn't see explicit columns. 
        // I'll skip adding the where clause for rating unless I'm sure, to avoid crashing. 
        // Wait, the show view uses static ratings. I'll skip DB filtering for rating for now.

        // Sort
        switch ($request->input('sort')) {
            case 'newest':
                $query->orderBy('publication_year', 'desc');
                break;
            case 'oldest':
                $query->orderBy('publication_year', 'asc');
                break;
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(12)->withQueryString();
        $genres = \App\Models\Genre::all();
        $countries = \App\Models\Country::whereHas('authors', function ($q) {
            $q->has('books');
        })->orderBy('name')->get();

        return view('pages.books.index', compact('books', 'genres', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['authors', 'reviews.user', 'genre', 'language']);
        return view('pages.books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
