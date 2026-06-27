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
        Schema::table('ujians', function (Blueprint $table) {
            $table->enum('tipe', ['ganda', 'essay'])->default('ganda')->after('judul');
            $table->text('teks_essay')->nullable()->after('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'teks_essay']);
        });
    }
};
