<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // Seguir a usuarios
    public function up(): void
    {
        Schema::create('follows', function (Blueprint $table) {

            $table->id();

            $table->string('follower_email');
            $table->foreign('follower_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->string('followed_email');
            $table->foreign('followed_email')
                ->references('email')
                ->on('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['follower_email', 'followed_email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
