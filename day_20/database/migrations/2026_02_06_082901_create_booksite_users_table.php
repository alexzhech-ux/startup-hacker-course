<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booksite_users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->string('password');
            $table->foreignId('role')
                ->constrained('booksite_roles')
                ->cascadeOnDelete();
        });

        DB::statement(
            'ALTER TABLE booksite_users ADD COLUMN votes_books_rating_id bigint[]'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('booksite_users');
    }
};