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
        Schema::create('reports', function (Blueprint $table) {
            $table->id('id_report');
            $table->foreignId('user_id')->constrained('users', 'id_user')->onDelete('restrict');
            $table->unsignedTinyInteger('masa_pajak');
            $table->year('tahun_pajak');
            $table->enum('jenis_laporan', ['rekap_tagihan_excel', 'daftar_tagihan_pdf']);
            $table->string('nama_file', 255);
            $table->string('lokasi_file', 255);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
