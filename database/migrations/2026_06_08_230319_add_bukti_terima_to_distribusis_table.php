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
        Schema::table('distribusi', function (Blueprint $table) {
            $table->string('bukti_terima')->nullable()->after('dokumentasi');
            $table->date('tanggal_terima')->nullable()->after('bukti_terima');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distribusi', function (Blueprint $table) {
            $table->dropColumn(['bukti_terima', 'tanggal_terima']);
        });
    }
};
