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
        Schema::create('posyandu_balita', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_posyandu', ['Posyandu Jetis', 'Posyandu Blimbing', 'Posyandu Wonorejo','Posyandu Sayangan','Posyandu Bangunrejo','Posyandu Bancakan','Posyandu Tegalan']);
            $table->string('nama');
            $table->integer('umur_tahun');
            $table->integer('umur_bulan');
            $table->integer('rt');
            $table->integer('rw');
            $table->float('berat_badan');
            $table->float('tinggi_badan');
            $table->float('lingkar_kepala');
            $table->float('lingkar_lengan');
            $table->date('tanggal');
            $table->string('keterangan_lain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posyandu_balita');

    }
};
