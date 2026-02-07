<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('booksite_book_genre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('booksite_books')->cascadeOnDelete();
            $table->foreignId('genre_id')->constrained('genres')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booksite_book_genre');
    }
};