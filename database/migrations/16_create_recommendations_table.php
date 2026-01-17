<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    //Recomendaciones de libros entre usuarios
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {

            $table->id();

            $table->string('from_user_email');
            $table->foreign('from_user_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('to_user_email');
            $table->foreign('to_user_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('book_isbn', 17);
            $table->foreign('book_isbn')
                ->references('isbn')
                ->on('books')
                ->cascadeOnDelete();

            $table->text('message')->nullable();

            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->unique([
                'from_user_email',
                'to_user_email',
                'book_isbn'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
