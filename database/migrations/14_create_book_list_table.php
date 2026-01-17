<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
