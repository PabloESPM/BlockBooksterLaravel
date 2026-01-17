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
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            $table->string('provider'); // amazon, fnac, casa_del_libro, etc.

            $table->enum('format', [
                'paperback',
                'hardcover',
                'ebook',
                'audiobook'
            ]);

            $table->string('country', 2)->default('es');

            $table->string('affiliate_url');

            $table->boolean('active')->default(true);

            $table->timestamps();

            $table->unique([
                'book_isbn',
                'provider',
                'format',
                'country'
            ]);
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
