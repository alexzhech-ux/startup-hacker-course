<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booksite_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        DB::table('booksite_roles')->insert([
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'User'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('booksite_roles');
    }
};