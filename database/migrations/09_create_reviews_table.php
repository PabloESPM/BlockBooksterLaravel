<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {

            $table->id();

            // Relaciones
            $table->string('user_email');
            $table->foreign('user_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            //Comentarios y valoracion
            $table->string('title')->nullable();
            $table->text('body');

            $table->timestamps();

            // Un usuario solo puede reseñar una vez cada libro
            $table->unique(['user_email', 'book_isbn']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
