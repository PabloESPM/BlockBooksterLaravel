<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Popular: Authors with most books (approximation for popular)
        $popularAuthors = Author::withCount('books')->orderBy('books_count', 'desc')->take(6)->get();

        // Classics: Authors with oldest books (approximation) or just random old ones. 
        // For now, let's take some random ones as we don't have birthdate easily queryable or "classic" tag.
        // Or we can just take the first few IDs.
        $classicAuthors = Author::take(3)->get();

        // Most Rated: Authors with most reviews on their books (complex query, we'll mock with random or top books count for now)
        $mostRatedAuthors = Author::withCount('books')->inRandomOrder()->take(5)->get();

        // Rising Stars / New: Latest added authors
        $newAuthors = Author::latest()->take(4)->get();

        return view('pages.authors.index', compact('popularAuthors', 'classicAuthors', 'mostRatedAuthors', 'newAuthors'));
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
    public function store(StoreAuthorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $author->load(['country']);
        $books = $author->books()->with('reviews')->paginate(6);
        return view('pages.authors.show', compact('author', 'books'));
    }

    /**
     * Return paginated books for a given author as JSON (for AJAX load more).
     */
    public function books(Author $author)
    {
        $books = $author->books()->with('reviews')->paginate(6);

        $html = $books->map(function ($book) use ($author) {
            return view('components.book-card', [
                'title' => $book->title,
                'author' => $author->name . ' ' . $author->surname,
                'cover' => $book->cover,
                'id' => $book->isbn,
                'rating' => 0,
            ])->render();
        })->implode('');

        return response()->json([
            'html' => $html,
            'hasMore' => $books->hasMorePages(),
            'nextPage' => $books->currentPage() + 1,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        //
    }
}
