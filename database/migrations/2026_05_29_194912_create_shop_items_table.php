<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category');       // title, frame, sleeve, theme
            $table->string('name');
            $table->string('description')->default('');
            $table->unsignedInteger('price'); // 0 = free/default
            $table->json('data')->nullable(); // CSS / config data
            $table->string('preview')->default(''); // emoji or icon hint
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_items');
    }
};
