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
        Schema::create('catatan_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('periode', ['mingguan', 'bulanan', 'tahunan']);
            $table->unsignedTinyInteger('bulan')->nullable(); // 1-12
            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('minggu')->nullable(); // 1-5
            $table->text('catatan');
            $table->timestamps();

            // Unik per siswa per periode
            $table->unique(['user_id', 'periode', 'bulan', 'tahun', 'minggu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_siswa');
    }
};
