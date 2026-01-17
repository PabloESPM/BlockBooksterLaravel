<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;
use App\Models\Purchase;
use App\Models\Review;
use App\Models\FavList;
use App\Models\Recommendation;
use App\Models\Follow;
use App\Models\ListLike;
use App\Models\ReviewLike;
use App\Models\BookUser;
use App\Models\Language;
use App\Models\Genre;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0️⃣ Crear países (Importante hacerlo antes de Users/Authors)
        if(\App\Models\Country::count() == 0){
             \App\Models\Country::factory(10)->create();
        }
        $allCountries = \App\Models\Country::all();

        // 0.5️⃣ Crear Lenguajes
        if(Language::count() == 0) {
            $langs = [
                ['code' => 'es', 'name' => 'Español'],
                ['code' => 'en', 'name' => 'Inglés'],
                ['code' => 'fr', 'name' => 'Francés'],
                ['code' => 'de', 'name' => 'Alemán'],
                ['code' => 'it', 'name' => 'Italiano'],
                ['code' => 'pt', 'name' => 'Portugués'],
                ['code' => 'ca', 'name' => 'Catalán'],
                ['code' => 'zh', 'name' => 'Chino'],
                ['code' => 'ja', 'name' => 'Japonés'],
                ['code' => 'ot', 'name' => 'Otros'],
            ];
            foreach($langs as $l) {
                Language::create($l);
            }
        }
        $allLanguages = Language::all();

        // 0.8️⃣ Crear Géneros
        if(\App\Models\Genre::count() == 0) {
            $genres = [
                'Ficción', 'No Ficción', 'Misterio', 'Thriller', 'Romance',
                'Fantasía', 'Ciencia Ficción', 'Terror', 'Biografía',
                'Historia', 'Poesía', 'Ensayo', 'Infantil', 'Juvenil', 'Autoayuda'
            ];
            foreach($genres as $g) {
                \App\Models\Genre::create(['name' => $g]);
            }
        }
        $allGenres = \App\Models\Genre::all();

        // 1️⃣ Crear autores y libros
        $authors = Author::factory(15)->create();
        $allBooks = collect();

        $authors->each(function ($author) use (&$allBooks, $allCountries) {
            $books = Book::factory(rand(1, 5))->create();
            $author->books()->attach($books->pluck('isbn')); // pivot table author_book

            // Guardar libros para relacionarlos con usuarios
            $allBooks = $allBooks->merge($books);

            // 2️⃣ Crear compras por libro, evitando duplicados
            $providers = ['Amazon', 'Fnac', 'Casa del Libro', 'El Corte Inglés'];
            $formats   = ['paperback', 'hardcover', 'ebook', 'audiobook'];
            // $countries not needed here as we use country_id now

            $books->each(function ($book) use ($providers, $formats, $allCountries) {
                $combinations = collect($providers)
                    ->crossJoin($formats, $allCountries)
                    ->shuffle()
                    ->take(rand(2, 4)); // 2-4 enlaces por libro

                foreach ($combinations as [$provider, $format, $country]) {
                    Purchase::create([
                        'book_isbn'     => $book->isbn,
                        'provider'      => $provider,
                        'format'        => $format,
                        'country_id'    => $country->id,
                        'affiliate_url' => fake()->url(),
                        'active'        => fake()->boolean(80),
                    ]);
                }
            });
        });

        // 3️⃣ Crear usuarios
        $users = User::factory(30)->create();
        $users[0]->update(['type' => 'admin']);
        foreach(array_slice($users->toArray(), 1, 5) as $worker){
            $workerModel = User::where('email', $worker['email'])->first();
            $workerModel->update(['type' => 'worker']);
        }

        // 4️⃣ Relacionar usuarios con libros y contenido
        $users->each(function($user) use ($allBooks, $users) {

            // Libros leídos (BookUser)
            $readBooks = $allBooks->random(rand(1,5));
            foreach($readBooks as $book){
                BookUser::create([
                    'user_id' => $user->id,
                    'book_isbn' => $book->isbn,
                    'status' => 'read',
                    'started_at' => now()->subMonths(rand(1,12)),
                    'finished_at' => now(),
                    'rating' => rand(1,5)
                ]);
            }

            // Reviews
            $readBooks->each(function($book) use ($user){
                Review::factory(1)->create([
                    'user_id' => $user->id,
                    'book_isbn' => $book->isbn
                ]);
            });

            // Listas de favoritos
            $lists = FavList::factory(rand(1,5))->create(['user_id' => $user->id]);
            $lists->each(function($list) use ($readBooks){
                $list->books()->attach(
                    $readBooks->random(rand(1, min(5, $readBooks->count())))->pluck('isbn')
                );
            });

            // Recomendaciones
            $usersToRecommend = $users->where('id', '!=', $user->id)->random(rand(1,5));
            foreach($usersToRecommend as $other){
                Recommendation::create([
                    'from_user_id' => $user->id,
                    'to_user_id' => $other->id,
                    'book_isbn' => $readBooks->random()->isbn,
                    'message' => 'Te recomiendo este libro!'
                ]);
            }

            // Follows
            $usersToFollow = $users->where('id', '!=', $user->id)->random(rand(1,5));
            foreach($usersToFollow as $other){
                Follow::create([
                    'follower_id' => $user->id,
                    'followed_id' => $other->id,
                ]);
            }

            // Likes en Reviews
            $allReviews = Review::inRandomOrder()->take(rand(1,5))->get();
            foreach($allReviews as $review){
                ReviewLike::create([
                    'user_id' => $user->id,
                    'review_id' => $review->id
                ]);
            }

            // Likes en Listas
            $allLists = FavList::inRandomOrder()->take(rand(1,5))->get();
            foreach($allLists as $list){
                ListLike::create([
                    'user_id' => $user->id,
                    'list_id' => $list->id
                ]);
            }

        });
    }
}
