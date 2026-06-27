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
        Schema::table('tugas', function (Blueprint $table) {
            $table->string('mata_pelajaran')->nullable()->after('id_kelas');
        });
        Schema::table('materis', function (Blueprint $table) {
            $table->string('mata_pelajaran')->nullable()->after('id_kelas');
        });
        Schema::table('ujians', function (Blueprint $table) {
            $table->string('mata_pelajaran')->nullable()->after('id_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('mata_pelajaran');
        });
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn('mata_pelajaran');
        });
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropColumn('mata_pelajaran');
        });
    }
};
