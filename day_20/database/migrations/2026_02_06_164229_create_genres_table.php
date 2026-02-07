<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        DB::table('genres')->insert([
            ['name' => 'Повесть'],
            ['name' => 'Эпос'],
            ['name' => 'Лирика'],
            ['name' => 'Драма'],
            ['name' => 'Эротика'],
            ['name' => 'Фэнтези'],
            ['name' => 'Фантастика'],
            ['name' => 'Справочник'],
            ['name' => 'Учебник'],
            ['name' => 'Эссе'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};