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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crear autores y libros
        $authors = Author::factory(15)->create();
        $allBooks = collect();

        $authors->each(function ($author) use (&$allBooks) {
            $books = Book::factory(rand(1, 5))->create();
            $author->books()->attach($books->pluck('isbn')); // pivot table author_book

            // Guardar libros para relacionarlos con usuarios
            $allBooks = $allBooks->merge($books);

            // 2️⃣ Crear compras por libro, evitando duplicados
            $providers = ['Amazon', 'Fnac', 'Casa del Libro', 'El Corte Inglés'];
            $formats   = ['paperback', 'hardcover', 'ebook', 'audiobook'];
            $countries = ['es', 'fr', 'de', 'it', 'pt'];

            $books->each(function ($book) use ($providers, $formats, $countries) {
                $combinations = collect($providers)
                    ->crossJoin($formats, $countries)
                    ->shuffle()
                    ->take(rand(2, 4)); // 2-4 enlaces por libro

                foreach ($combinations as [$provider, $format, $country]) {
                    Purchase::create([
                        'book_isbn'     => $book->isbn,
                        'provider'      => $provider,
                        'format'        => $format,
                        'country'       => $country,
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
            $workerModel = User::find($worker['email']);
            $workerModel->update(['type' => 'worker']);
        }

        // 4️⃣ Relacionar usuarios con libros y contenido
        $users->each(function($user) use ($allBooks, $users) {

            // Libros leídos (BookUser)
            $readBooks = $allBooks->random(rand(1,5));
            foreach($readBooks as $book){
                BookUser::create([
                    'user_email' => $user->email,
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
                    'user_email' => $user->email,
                    'book_isbn' => $book->isbn
                ]);
            });

            // Listas de favoritos
            $lists = FavList::factory(rand(1,5))->create(['user_email' => $user->email]);
            $lists->each(function($list) use ($readBooks){
                $list->books()->attach(
                    $readBooks->random(rand(1, min(5, $readBooks->count())))->pluck('isbn')
                );
            });

            // Recomendaciones
            $usersToRecommend = $users->where('email', '!=', $user->email)->random(rand(1,5));
            foreach($usersToRecommend as $other){
                Recommendation::create([
                    'from_user_email' => $user->email,
                    'to_user_email' => $other->email,
                    'book_isbn' => $readBooks->random()->isbn,
                    'message' => 'Te recomiendo este libro!'
                ]);
            }

            // Follows
            $usersToFollow = $users->where('email', '!=', $user->email)->random(rand(1,5));
            foreach($usersToFollow as $other){
                Follow::create([
                    'follower_email' => $user->email,
                    'followed_email' => $other->email,
                ]);
            }

            // Likes en Reviews
            $allReviews = Review::inRandomOrder()->take(rand(1,5))->get();
            foreach($allReviews as $review){
                ReviewLike::create([
                    'user_email' => $user->email,
                    'review_id' => $review->id
                ]);
            }

            // Likes en Listas
            $allLists = FavList::inRandomOrder()->take(rand(1,5))->get();
            foreach($allLists as $list){
                ListLike::create([
                    'user_email' => $user->email,
                    'list_id' => $list->id
                ]);
            }

        });
    }
}
