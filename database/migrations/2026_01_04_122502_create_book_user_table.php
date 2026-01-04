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
        Schema::create('book_user', function (Blueprint $table) {

            $table->id();

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
            // Estatus de la lectura
            $table->enum('status', ['pending', 'reading', 'read'])
                ->default('pending');
            // Ha empezado a leerlo
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            //Valoracion en estrellas u otros
            $table->tinyInteger('rating')->nullable(); // 1–5

            $table->unique(['user_email', 'book_isbn']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_user');
    }
};
