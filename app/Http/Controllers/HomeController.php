<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $latestBooks = Book::with('authors')->latest()->take(6)->get();

        // Mostrar algunos "mejor valorados",
        $bestRatedBooks = Book::with('authors')->take(5)->get();

        // Autores emergentes para la página de inicio (Top 7 por seguidores en los últimos 30 días)
        $risingStars = \App\Models\Author::withCount([
            'followers' => function ($query) {
                $query->where('author_followers.created_at', '>=', now()->subDays(30));
            }
        ])
            ->withCount('books')
            ->orderByDesc('followers_count')
            ->take(7)
            ->get();

        // Listas Destacadas (Top 4 públicas con más likes en los últimos 30 días)
        $featuredLists = \App\Models\FavList::select('fav_lists.*')
            ->where('visibility', 'public')
            ->with(['user', 'likes'])
            ->withCount(['books', 'likes'])
            ->leftJoin('list_likes', function ($join) {
                $join->on('fav_lists.id', '=', 'list_likes.list_id')
                    ->where('list_likes.created_at', '>=', now()->subDays(30));
            })
            ->groupBy('fav_lists.id')
            ->orderByDesc(\DB::raw('COUNT(list_likes.id)'))
            ->take(4)
            ->get();

        // Opiniones brutales (Mejores reseñas del mes)
        $brutalOpinions = \App\Models\Review::with(['user', 'book'])
            ->withCount('likes')
            ->where('created_at', '>=', now()->subMonth())
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        // Géneros principales (Top 6 por valoración promedio en los últimos 30 días)
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

