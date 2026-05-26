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
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('card_id');       // e.g. "sv8-123"
            $table->string('card_name');
            $table->string('set_id');
            $table->string('rarity')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'card_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_cards');
    }
};
