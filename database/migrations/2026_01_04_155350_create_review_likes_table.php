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
        Schema::create('review_likes', function (Blueprint $table) {

            $table->id();

            $table->string('user_email');
            $table->foreign('user_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreignId('review_id')
                ->constrained('reviews')
                ->cascadeOnDelete();

            $table->timestamps();

            // Un usuario solo puede dar like una vez a una review
            $table->unique(['user_email', 'review_id']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_likes');
    }
};
