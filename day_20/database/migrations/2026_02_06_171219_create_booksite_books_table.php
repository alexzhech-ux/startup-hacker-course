<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booksite_books', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('cover')->nullable();
        $table->boolean('is_for_adult')->default(false);
        $table->unsignedInteger('rating_1')->default(0);
        $table->unsignedInteger('rating_2')->default(0);
        $table->unsignedInteger('rating_3')->default(0);
        $table->unsignedInteger('rating_4')->default(0);
        $table->unsignedInteger('rating_5')->default(0);

        $table->foreignId('user_id')->constrained('booksite_users');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('booksite_books');
    }
};