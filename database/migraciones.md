# Create Tablas del Proyecto **BlockBookster**

Este documento consolida **todas las migraciones del proyecto** en un formato limpio, consistente y orientado a documentación técnica.

* Código **funcionalmente idéntico** al original.
* Estilo **PSR-12 / Laravel**.
* Comentarios preservados y ampliados cuando aporta claridad.
* Bloques clasificados por **rol arquitectónico** (core, pivote, social, etc.).

---

## 🌍 Core Geográfico

### Countries (tabla base / referencia global)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone_code');
            $table->string('iso_code', 3)->unique(); // ISO 3166-1 alpha-2 / alpha-3
            $table->string('currency', 3);
            $table->string('continent');
            $table->string('timezone');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
```

---

## 👤 Core de Autenticación y Usuarios

### Users, Password Resets & Sessions (núcleo de autenticación)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('telephone');
            $table->string('password');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['admin', 'worker', 'user'])->default('user');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
```

---

## 🌐 Internacionalización

### Languages (catálogo ISO 639-1)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // ISO 639-1
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
```

---

## 📚 Dominio Editorial (Core)

### Genres (clasificación literaria)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
```

### Books (entidad central del dominio)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->string('isbn', 17)->primary();
            $table->string('title');
            $table->string('publisher');
            $table->year('publication_year');
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->integer('number_of_pages');
            $table->string('cover_path')->nullable();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
```

---

## ✍️ Autores y Relaciones Editoriales

### Authors (entidad autoral)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id(); // bigint autoincremental (PostgreSQL-friendly)
            $table->string('name');
            $table->string('surname')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
            $table->text('biography')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};
```

### Author ↔ Book (tabla pivote profesional)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->id();

            $table->foreignId('author_id')
                ->constrained('authors')
                ->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            // Metadatos profesionales
            $table->enum('role', ['author', 'coauthor', 'editor'])->default('author');
            $table->integer('author_order')->nullable();

            // Evita duplicados autor-libro
            $table->unique(['author_id', 'book_isbn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
```

---

## 📖 Interacción Usuario ↔ Libro

### Book ↔ User (estado de lectura)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            // Estatus de lectura
            $table->enum('status', ['pending', 'reading', 'read'])->default('pending');
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();

            // Valoración (1–5)
            $table->tinyInteger('rating')->nullable();

            $table->unique(['user_id', 'book_isbn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_user');
    }
};
```

### Reviews (contenido social)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            $table->string('title')->nullable();
            $table->text('body');
            $table->timestamps();

            // Un usuario solo puede reseñar una vez cada libro
            $table->unique(['user_id', 'book_isbn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
```

---

## ⭐ Listas, Likes y Social Graph

### Fav Lists (colecciones curadas)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fav_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('visibility', ['private', 'public', 'friends'])->default('private');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fav_lists');
    }
};
```

### Book ↔ List (tabla pivote ordenable)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_list', function (Blueprint $table) {
            $table->id();

            $table->foreignId('list_id')
                ->constrained('fav_lists')
                ->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            $table->integer('position')->nullable();
            $table->text('note')->nullable();
            $table->timestamp('added_at')->useCurrent();

            $table->unique(['list_id', 'book_isbn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_list');
    }
};
```

### List Likes

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('list_id')->constrained('fav_lists')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'list_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_likes');
    }
};
```

### Review Likes

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'review_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_likes');
    }
};
```

### Follows (grafo social)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('followed_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['follower_id', 'followed_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
```

---

## 💬 Recomendaciones y Monetización

### Recommendations (social + contenido)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')->references('isbn')->on('books')->cascadeOnDelete();
            $table->text('message')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['from_user_id', 'to_user_id', 'book_isbn']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
```

### Purchases (afiliación / monetización)

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            $table->string('provider'); // amazon, fnac, casa_del_libro, etc.
            $table->enum('format', ['paperback', 'hardcover', 'ebook', 'audiobook']);
            $table->foreignId('country_id')->constrained();
            $table->string('affiliate_url');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['book_isbn', 'provider', 'format', 'country_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
```
