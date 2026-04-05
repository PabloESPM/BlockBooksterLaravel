<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Book;
use App\Models\Author;
use App\Models\FavList;
use App\Models\Review;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;

new #[Layout('layouts.app')] #[Title('Welcome')] class extends Component {
    public function with()
    {
        // Libros más recientes
        $latestBooks = Book::with('authors')->latest()->take(6)->get();

        // Libros mejor valorados (ejemplo simple, se puede ajustar la lógica si es necesario)
        $bestRatedBooks = Book::with('authors')->take(5)->get();

        // Autores emergentes (Top 7 por seguidores en los últimos 30 días)
        $risingStars = Author::withCount([
            'followers' => function ($query) {
                $query->where('author_followers.created_at', '>=', now()->subDays(30));
            }
        ])
            ->withCount('books')
            ->orderByDesc('followers_count')
            ->take(7)
            ->get();

        // Listas Destacadas (Top 4 públicas con más likes en los últimos 30 días)
        $featuredLists = FavList::select('fav_lists.*')
            ->where('visibility', 'public')
            ->with(['user', 'likes'])
            ->withCount(['books', 'likes'])
            ->leftJoin('list_likes', function ($join) {
                $join->on('fav_lists.id', '=', 'list_likes.list_id')
                    ->where('list_likes.created_at', '>=', now()->subDays(30));
            })
            ->groupBy('fav_lists.id')
            ->orderByDesc(DB::raw('COUNT(list_likes.id)'))
            ->take(4)
            ->get();

        // Opiniones brutales (Mejores reseñas del mes)
        $brutalOpinions = Review::with(['user', 'book'])
            ->withCount('likes')
            ->where('created_at', '>=', now()->subMonth())
            ->orderByDesc('likes_count')
            ->take(3)
            ->get();

        // Géneros principales (Top 6 por valoración promedio en los últimos 30 días)
        $topGenres = Genre::select('genres.*')
            ->join('books', 'genres.id', '=', 'books.genre_id')
            ->join('reviews', 'books.isbn', '=', 'reviews.book_isbn')
            ->join('book_user', function ($join) {
                $join->on('reviews.book_isbn', '=', 'book_user.book_isbn')
                    ->on('reviews.user_id', '=', 'book_user.user_id');
            })
            ->where('reviews.created_at', '>=', now()->subDays(30))
            ->groupBy('genres.id')
            ->orderByDesc(DB::raw('AVG(book_user.rating)'))
            ->take(6)
            ->get();

        foreach ($topGenres as $genre) {
            $genre->top_books = Book::where('genre_id', $genre->id)
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

        return [
            'latestBooks' => $latestBooks,
            'bestRatedBooks' => $bestRatedBooks,
            'risingStars' => $risingStars,
            'featuredLists' => $featuredLists,
            'brutalOpinions' => $brutalOpinions,
            'topGenres' => $topGenres,
        ];
    }
}; ?>

<div>
    <!-- Encabezado -->
    <section class="mb-20 text-center border-b-4 border-black pb-16">
        <h1 class="text-5xl md:text-7xl font-display font-black tracking-tighter mb-6 uppercase leading-none">
            Sigue <span class="bg-brand-blue text-white px-2 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">libros</span><br>como si fueran prosa.
        </h1>
        <p class="text-xl font-bold mb-10 max-w-2xl mx-auto text-gray-700">
            La red social sin complicaciones para los amantes de los libros. <br>Valora. Opina. Comparte.
        </p>

        <!-- Barra de Busqueda -->
        <form action="{{ route('search') }}" method="GET" class="max-w-3xl mx-auto mb-10 flex gap-3">
            <div class="flex-1 relative">
                <input type="text" name="q" class="neo-input text-lg py-4 pl-12 w-full"
                    placeholder="Buscar libros, autores, usuarios, listas, géneros, ISBN..." required>
                <svg class="w-6 h-6 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button type="submit" class="neo-btn-primary px-8 text-lg">Buscar</button>
        </form>
    </section>

    <!-- Únete al club (registrarse)  -->
    @guest
        <section class="mb-20 bg-black text-white p-8 md:p-12 shadow-[8px_8px_0px_#000] relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-brand-yellow rounded-full opacity-20 blur-3xl"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black uppercase mb-2">Únete al club</h2>
                    <p class="font-bold text-gray-400">Crea tu perfil, lleva un registro de tus lecturas y únete al debate.</p>
                </div>
                <a href="{{ route('register') }}" wire:navigate
                    class="bg-brand-yellow text-black border-2 border-white font-black uppercase px-8 py-4 shadow-[4px_4px_0px_#fff] hover:translate-y-[-2px] hover:shadow-[6px_6px_0px_#fff] transition-all">
                    Crear cuenta
                </a>
            </div>
        </section>
    @endguest

    <div id="discovery"></div>

    <!-- Ultimas Novedades -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Últimas <span
                    class="text-brand-blue">Novedades</span></h2>
            <a href="{{ route('books.index') }}" wire:navigate
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VER TODOS</a>
        </div>

        <div class="flex overflow-x-auto pb-10 space-x-6 snap-x hide-scrollbar">
            @foreach($latestBooks as $book)
                <div class="w-48 flex-none snap-start">
                    <x-book-card id="{{ $book->isbn }}" :title="$book->title" :author="$book->authors->first()->name ?? 'Unknown'"
                        :cover="$book->cover ?? 'https://via.placeholder.com/300x450'"
                        :rating="4.5" />
                </div>
            @endforeach
        </div>
    </section>

    <!-- Mejor Valorados -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Mejor <span
                    class="text-brand-yellow text-shadow-neo">Valorados</span></h2>
            <a href="{{ route('books.index') }}" wire:navigate
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VER
                TODOS</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($bestRatedBooks as $book)
                <x-book-card id="{{ $book->isbn }}" :title="$book->title" :author="$book->authors->first()->name ?? 'Unknown'"
                    :cover="$book->cover ?? 'https://via.placeholder.com/300x450'"
                    :rating="5.0" />
            @endforeach
        </div>
    </section>

    <!-- Top Géneros -->
    <section class="mb-16">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Top <span
                    class="underline decoration-4 decoration-brand-blue">Géneros</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($topGenres as $genre)
                <x-genre-card :genre="$genre" :books="$genre->top_books" />
            @endforeach
        </div>
    </section>

    <!-- Autores emergentes -->
    <section class="mb-20">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Autores <span
                    class="bg-black text-white px-2">emergentes</span></h2>
            <a href="{{ route('authors.index') }}" wire:navigate
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VER TODOS</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-6">
            @foreach($risingStars as $author)
                <x-author-card :author="$author" />
            @endforeach
        </div>
    </section>

    <!-- Listas Destacadas -->
    <section class="mb-20">
        <div class="flex items-end justify-between mb-8 border-b-2 border-black pb-2">
            <h2 class="text-3xl font-display font-black uppercase tracking-tight">Listas <span
                    class="text-brand-blue">Destacadas</span></h2>
            <a href="{{ route('lists.index') }}" wire:navigate
                class="font-bold underline decoration-2 decoration-brand-yellow hover:bg-brand-yellow hover:text-black transition-colors px-2">VER
                TODOS</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredLists as $list)
                <x-list-card :list="$list" />
            @endforeach
        </div>
    </section>

    <!-- Brutales Opiniones -->
    <section class="mb-16">
        <h2 class="text-3xl font-display font-black uppercase tracking-tight mb-8 border-b-2 border-black pb-2">Opiniones
            Brutales</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse($brutalOpinions as $review)
                <x-review-card :review="$review" />
            @empty
                <div class="col-span-3 text-center py-12 border-2 border-dashed border-gray-300 bg-gray-50">
                    <p class="text-xl font-bold uppercase text-gray-400">Sin opiniones brutales todavía este mes.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
