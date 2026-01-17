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
        Schema::create('books', function (Blueprint $table) {
            $table->string('isbn', 17)->primary();
            $table->string('title');
            $table->string('publisher');
            $table->year('publication_year');
            $table->enum('language', [
                'es','en','fr','de','it','pt','ca','zh','ja','otros'
            ])->default('es');
            $table->integer('number_of_pages');
            $table->string('cover_path')->nullable();
            $table->enum('genre', [
                'ficcion','no_ficcion','misterio','thriller','romance',
                'fantasia','ciencia_ficcion','terror','biografia',
                'historia','poesia','ensayo','infantil','juvenil','autoayuda'
            ]);
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
