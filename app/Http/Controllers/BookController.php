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
    public function index(\Illuminate\Http\Request $request)
    {
        $query = Book::with('authors');

        // Búsqueda (Título/Autor/ISBN) desde Búsqueda Avanzada o búsqueda simple
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

        // Filtros del sidebar
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

        // Calculamos la valoración media para tenerla disponible en la vista
        // La almacenamos con el alias 'average_rating'
        $query->withAvg('users as average_rating', 'book_user.rating');

        // Filtrado por valoración
        // Comprobamos si el usuario ha seleccionado una valoración (ej. 4 estrellas)
        // Usamos una subconsulta en el 'where' para evitar problemas de paginación que suelen ocurrir al usar 'having'
        if ($request->filled('rating')) {
            $query->where(function ($subquery) {
                $subquery->selectRaw('avg(rating)')
                    ->from('book_user')
                    ->whereColumn('book_isbn', 'books.isbn');
            }, '>=', $request->rating);
        }

        // Ordenación al mostrar
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
        $book->load([
            'authors',
            'genre',
            'language'
        ]);

        $reviews = $book->reviews()
            ->with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->paginate(3);

        return view('pages.books.show', compact('book', 'reviews'));
    }

    /**
     * AJAX: Cargar más reseñas.
     */
    public function loadReviews(Book $book)
    {
        $reviews = $book->reviews()
            ->with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->paginate(3);

        $html = view('components.review-card-list', ['reviews' => $reviews, 'showBook' => false])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $reviews->hasMorePages(),
            'nextPage' => $reviews->currentPage() + 1,
        ]);
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
