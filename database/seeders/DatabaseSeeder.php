<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    Author,
    Book,
    Purchase,
    Review,
    FavList,
    Recommendation,
    Follow,
    ListLike,
    ReviewLike,
    BookUser,
    Language,
    Genre,
    Country
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /** ️⃣ Datos base */
        Country::factory(10)->create();

        if (Language::count() === 0) {
            collect([
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
            ])->each(fn ($l) => Language::create($l));
        }

        if (Genre::count() === 0) {
            collect([
                'Ficción', 'No Ficción', 'Misterio', 'Thriller', 'Romance',
                'Fantasía', 'Ciencia Ficción', 'Terror', 'Biografía',
                'Historia', 'Poesía', 'Ensayo', 'Infantil', 'Juvenil', 'Autoayuda'
            ])->each(fn ($g) => Genre::create(['name' => $g]));
        }

        /** 1️⃣ Autores y libros */
        $authors   = Author::factory(15)->create();
        $allBooks = collect();
        $countries = Country::all();

        $authors->each(function ($author) use (&$allBooks, $countries) {
            $books = Book::factory(rand(1, 5))->create();
            $author->books()->attach($books->pluck('isbn'));
            $allBooks = $allBooks->merge($books);

            $providers = ['Amazon', 'Fnac', 'Casa del Libro', 'El Corte Inglés'];
            $formats   = ['paperback', 'hardcover', 'ebook', 'audiobook'];

            $books->each(function ($book) use ($providers, $formats, $countries) {
                collect($providers)
                    ->crossJoin($formats, $countries)
                    ->shuffle()
                    ->take(rand(2, 4))
                    ->each(function ($combo) use ($book) {
                        [$provider, $format, $country] = $combo;

                        Purchase::create([
                            'book_isbn'     => $book->isbn,
                            'provider'      => $provider,
                            'format'        => $format,
                            'country_id'    => $country->id,
                            'affiliate_url' => fake()->url(),
                            'active'        => fake()->boolean(80),
                        ]);
                    });
            });
        });

        /** Usuarios */
        $users = User::factory(30)->create();

        // Roles
        $users->first()->update(['type' => 'admin']);
        $users->skip(1)->take(5)->each(fn ($u) => $u->update(['type' => 'worker']));

        // Asegurar perfiles visibles para interacción social
        $users->take(20)->each(
            fn ($u) => $u->update(['profile_visibility' => 'public'])
        );

        /** Relaciones usuario ↔ libro */
        $users->each(function (User $user) use ($allBooks, $users) {
            $readBooks = $allBooks->random(rand(1, 5));

            foreach ($readBooks as $book) {
                BookUser::create([
                    'user_id'     => $user->id,
                    'book_isbn'   => $book->isbn,
                    'status'      => 'read',
                    'started_at'  => now()->subMonths(rand(1, 12)),
                    'finished_at' => now(),
                    'rating'      => rand(1, 5),
                ]);
            }

            $readBooks->each(fn ($book) =>
            Review::factory()->create([
                'user_id'   => $user->id,
                'book_isbn'=> $book->isbn,
            ])
            );

            $lists = FavList::factory(rand(1, 4))->create(['user_id' => $user->id]);
            $lists->each(fn ($list) =>
            $list->books()->attach(
                $readBooks->random(rand(1, min(5, $readBooks->count())))->pluck('isbn')
            )
            );

            /** Recomendaciones (solo a perfiles visibles) */
            $targets = $users
                ->where('id', '!=', $user->id)
                ->whereIn('profile_visibility', ['public', 'followers'])
                ->random(rand(1, 3));

            foreach ($targets as $target) {
                Recommendation::create([
                    'from_user_id' => $user->id,
                    'to_user_id'   => $target->id,
                    'book_isbn'    => $readBooks->random()->isbn,
                    'message'      => 'Te recomiendo este libro 📚',
                ]);
            }
        });

        /** Follows y Friends (follow mutuo) */
        $users->each(function (User $user) use ($users) {
            $candidates = $users
                ->where('id', '!=', $user->id)
                ->whereIn('profile_visibility', ['public', 'followers'])
                ->random(rand(1, 4));

            foreach ($candidates as $other) {
                Follow::firstOrCreate([
                    'follower_id' => $user->id,
                    'followed_id' => $other->id,
                ]);

                // 30% de probabilidad de amistad (follow mutuo)
                if (rand(1, 100) <= 30) {
                    Follow::firstOrCreate([
                        'follower_id' => $other->id,
                        'followed_id' => $user->id,
                    ]);
                }
            }
        });

        /** Likes */
        Review::all()->each(function ($review) use ($users) {
            $users->random(rand(0, 5))->each(fn ($u) =>
            ReviewLike::firstOrCreate([
                'user_id'   => $u->id,
                'review_id' => $review->id,
            ])
            );
        });

        FavList::all()->each(function ($list) use ($users) {
            $users->random(rand(0, 5))->each(fn ($u) =>
            ListLike::firstOrCreate([
                'user_id' => $u->id,
                'list_id' => $list->id,
            ])
            );
        });
    }
}

