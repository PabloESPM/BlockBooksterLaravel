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

     // Rellena la BD.
    public function run(): void
    {

        // Genera 10 países aleatorios mediante factory.
        Country::factory(10)->create();

        // Creación de idiomas.
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

        // Creación de géneros literarios.
        if (Genre::count() === 0) {
            collect([
                'Ficción', 'No Ficción', 'Misterio', 'Thriller', 'Romance',
                'Fantasía', 'Ciencia Ficción', 'Terror', 'Biografía',
                'Historia', 'Poesía', 'Ensayo', 'Infantil', 'Juvenil', 'Autoayuda'
            ])->each(fn ($g) => Genre::create(['name' => $g]));
        }

        // Se generan 15 autores.
        $authors   = Author::factory(15)->create();

        // Colección global donde almacenaremos todos los libros creados.
        $allBooks = collect();

        // Se cargan todos los países para asignarlos a compras.
        $countries = Country::all();

        $authors->each(function ($author) use (&$allBooks, $countries) {

            // Cada autor publica entre 1 y 5 libros.
            $books = Book::factory(rand(1, 5))->create();

            // Relación muchos a muchos autor / libro usando ISBN como clave.
            $author->books()->attach($books->pluck('isbn'));

            // Se almacenan en colección global para uso posterior.
            $allBooks = $allBooks->merge($books);

            // Proveedores simulados de venta.
            $providers = ['Amazon', 'Fnac', 'Casa del Libro', 'El Corte Inglés'];

            // Formatos disponibles.
            $formats   = ['paperback', 'hardcover', 'ebook', 'audiobook'];

            // Para cada libro se generan combinaciones proveedor + formato + país.
            $books->each(function ($book) use ($providers, $formats, $countries) {

                collect($providers)
                    ->crossJoin($formats, $countries)
                    ->shuffle()                      // Aleatoriza combinaciones
                    ->take(rand(2, 4))               // Seleccionan entre 2 y 4 combinaciones aleatorias
                    ->each(function ($combo) use ($book) {

                        [$provider, $format, $country] = $combo;
                        // Se crean registros de compra
                        Purchase::create([
                            'book_isbn'     => $book->isbn,
                            'provider'      => $provider,
                            'format'        => $format,
                            'country_id'    => $country->id,
                            'affiliate_url' => fake()->url(),
                            'active'        => fake()->boolean(80), // 80% activas
                        ]);
                    });
            });
        });

        // USUARIOS Y ROLES
        // Genera 30 usuarios.
        $users = User::factory(30)->create();

        // Primer usuario administrador.
        $users->first()->update(['type' => 'admin']);

        // Cinco usuarios tipo trabajador.
        $users->skip(1)->take(5)
            ->each(fn ($u) => $u->update(['type' => 'worker']));

        // 20 perfiles sean públicos.
        $users->take(20)->each(
            fn ($u) => $u->update(['profile_visibility' => 'public'])
        );

        // RELACIÓN USUARIO / LIBRO
        $users->each(function (User $user) use ($allBooks, $users) {

            // Cada usuario ha leído entre 1 y 5 libros.
            $readBooks = $allBooks->random(rand(1, 5));

            foreach ($readBooks as $book) {

                // Registro en tabla pivote book_user.
                BookUser::create([
                    'user_id'     => $user->id,
                    'book_isbn'   => $book->isbn,
                    'status'      => 'read',
                    'started_at'  => now()->subMonths(rand(1, 12)),
                    'finished_at' => now(),
                    'rating'      => rand(1, 5),
                ]);
            }

            // Creación de reseñas asociadas a los libros leídos.
            $readBooks->each(fn ($book) =>
            Review::factory()->create([
                'user_id'   => $user->id,
                'book_isbn' => $book->isbn,
            ])
            );

            // Listas favoritas del usuario, cada usuario tiene entre 1 y 4 listas.
            $lists = FavList::factory(rand(1, 4))
                ->create(['user_id' => $user->id]);

            $lists->each(fn ($list) =>
            $list->books()->attach(
                $readBooks
                    ->random(rand(1, min(5, $readBooks->count())))
                    ->pluck('isbn')
            )
            );

            //RECOMENDACIONES
            //Solo se pueden enviar a perfiles visiblicos de segidores.
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

         // SISTEMA DE FOLLOW Y AMISTAD
        $users->each(function (User $user) use ($users) {
            // Probabilidad de visivilidad del perfil.
            $candidates = $users
                ->where('id', '!=', $user->id)
                ->whereIn('profile_visibility', ['public', 'followers'])
                ->random(rand(1, 4));

            foreach ($candidates as $other) {

                // Follow unidireccional.
                Follow::firstOrCreate([
                    'follower_id' => $user->id,
                    'followed_id' => $other->id,
                ]);

                // 30% probabilidad de follow mutuo.
                if (rand(1, 100) <= 30) {
                    Follow::firstOrCreate([
                        'follower_id' => $other->id,
                        'followed_id' => $user->id,
                    ]);
                }
            }
        });

        // Likes en reseñas entre 0 y 5.
        Review::all()->each(function ($review) use ($users) {

            $users->random(rand(0, 5))->each(fn ($u) =>
            ReviewLike::firstOrCreate([
                'user_id'   => $u->id,
                'review_id' => $review->id,
            ])
            );
        });

        // Likes en listas entre 0 y 5.
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


