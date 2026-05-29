<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('active_title')->nullable()->after('avatar');
            $table->string('active_frame')->nullable()->after('active_title');
            $table->string('active_sleeve')->nullable()->after('active_frame');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['active_title', 'active_frame', 'active_sleeve']);
        });
    }
};
