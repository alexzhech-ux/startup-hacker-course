<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booksite_books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('owner_id')
                ->constrained('booksite_users')
                ->cascadeOnDelete();
            $table->string('cover')->nullable();
            $table->boolean('for_adult')->default(false);
            $table->date('add_date');
            $table->timestamps();
        });

        DB::statement(
            'ALTER TABLE booksite_books ADD COLUMN genres_id bigint[]'
        );

        DB::statement(
            'ALTER TABLE booksite_books ADD COLUMN rating bigint[] NOT NULL DEFAULT ARRAY[0,0,0,0,0]'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('booksite_books');
    }
};