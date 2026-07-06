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
        Schema::create('permintaan_detail', function (Blueprint $table) {
            $table->id('id_permintaan_detail');
            $table->foreignId('permintaan_id')->constrained('permintaan', 'id_permintaan')->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained('barang', 'id_barang')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->text('alasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_detail');
    }
};
