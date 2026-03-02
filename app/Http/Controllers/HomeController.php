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

        // Rising Stars Authors for Home (Top 7 by followers in last 30 days)
        $risingStars = \App\Models\Author::withCount([
            'followers' => function ($query) {
                $query->where('author_followers.created_at', '>=', now()->subDays(30));
            }
        ])
            ->withCount('books')
            ->orderByDesc('followers_count')
            ->take(7)
            ->get();

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

        // Top Genres (Top 6 by avg rating in last 30 days)
        $topGenres = \App\Models\Genre::select('genres.*')
            ->join('books', 'genres.id', '=', 'books.genre_id')
            ->join('reviews', 'books.isbn', '=', 'reviews.book_isbn')
            ->join('book_user', function ($join) {
                $join->on('reviews.book_isbn', '=', 'book_user.book_isbn')
                    ->on('reviews.user_id', '=', 'book_user.user_id');
            })
            ->where('reviews.created_at', '>=', now()->subDays(30))
            ->groupBy('genres.id')
            ->orderByDesc(\DB::raw('AVG(book_user.rating)'))
            ->take(6)
            ->get();

        foreach ($topGenres as $genre) {
            $genre->top_books = \App\Models\Book::where('genre_id', $genre->id)
                ->select('books.*')
                ->join('book_user', 'books.isbn', '=', 'book_user.book_isbn')
                ->join('reviews', function ($join) {
                    $join->on('book_user.book_isbn', '=', 'reviews.book_isbn')
                        ->on('book_user.user_id', '=', 'reviews.user_id');
                })
                ->where('reviews.created_at', '>=', now()->subDays(30))
                ->selectRaw('AVG(book_user.rating) as reviews_avg_rating')
                ->groupBy('books.isbn')
                ->orderByDesc('reviews_avg_rating')
                ->take(5)
                ->get();
        }

        return view('pages.home.index', compact(
            'latestBooks',
            'bestRatedBooks',
            'risingStars',
            'featuredLists',
            'brutalOpinions',
            'topGenres'
        ));
    }
}
