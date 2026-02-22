<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestBooks = Book::with('authors')->latest()->take(6)->get();
        // Assuming we want to show some "best rated", but without a calculated rating column, 
        // we'll just take some books. Adding 'reviews' if we want to calculate it in view or 
        // if we implement a scope later.
        $bestRatedBooks = Book::with('authors')->take(5)->get();

        // Rising Stars Authors for Home
        $risingStars = \App\Models\Author::latest()->take(5)->get();

        // New Lists
        $featuredLists = \App\Models\FavList::where('visibility', 'public')
            ->with(['user'])
            ->withCount('books')
            ->latest()
            ->take(4)
            ->get();

        // Brutal Opinions (Top reviews of the month)
        $brutalOpinions = \App\Models\Review::with(['user', 'book'])
            ->withCount('likes')
            ->where('created_at', '>=', now()->subMonth())
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        return view('pages.home.index', compact('latestBooks', 'bestRatedBooks', 'risingStars', 'featuredLists', 'brutalOpinions'));
    }
}
