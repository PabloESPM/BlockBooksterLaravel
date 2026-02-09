<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavListRequest;
use App\Http\Requests\UpdateFavListRequest;
use App\Models\FavList;

class FavListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lists = FavList::where('visibility', 'public')
            ->with([
                'user',
                'books' => function ($query) {
                    $query->take(4); // Preview 4 books for the card
                }
            ])
            ->withCount('books')
            ->latest()
            ->paginate(12);

        return view('pages.lists.index', compact('lists'));
    }

    /**
     * Display the user's lists in the dashboard.
     */
    public function dashboardIndex()
    {
        $lists = auth()->user()->lists()->latest()->get();
        return view('pages.dashboard.lists', compact('lists'));
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
    public function store(StoreFavListRequest $request)
    {
        $request->user()->lists()->create($request->validated());

        return redirect()->route('dashboard.lists')->with('success', 'List created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(FavList $list)
    {
        // Load relationships: user, books (with authors)
        $list->load(['user', 'books.authors']);
        return view('pages.lists.show', compact('list'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FavList $favList)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFavListRequest $request, FavList $list)
    {
        // Ensure user owns the list
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->update($request->validated());

        return redirect()->back()->with('success', 'List updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FavList $list)
    {
        // Ensure user owns the list
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->delete();

        return redirect()->route('dashboard.lists')->with('success', 'List deleted successfully!');
    }
    /**
     * Attach a book to the list.
     */
    public function attachBook(\Illuminate\Http\Request $request, FavList $list)
    {
        // Ensure user owns the list
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'book_isbn' => 'required|exists:books,isbn',
        ]);

        $bookIsbn = $request->input('book_isbn');

        if (!$list->books()->where('book_isbn', $bookIsbn)->exists()) {
            $list->books()->attach($bookIsbn, ['added_at' => now()]);
            return back()->with('success', 'Book added to list successfully!');
        }

        return back()->with('info', 'Book is already in this list.');
    }

    /**
     * Create a new list and attach a book immediately.
     */
    public function storeAndAttach(StoreFavListRequest $request)
    {
        $request->validate([
            'book_isbn' => 'required|exists:books,isbn',
            // other fields are validated by StoreFavListRequest
        ]);

        $list = $request->user()->lists()->create($request->validated());

        $list->books()->attach($request->input('book_isbn'), ['added_at' => now()]);

        return back()->with('success', 'List created and book added successfully!');
    }
}
