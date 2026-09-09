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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->foreignId('vendor_id')->constrained('vendors', 'id_vendor')->onDelete('restrict');
            $table->string('nomor_invoice', 100);
            $table->date('tanggal_invoice');
            $table->unsignedTinyInteger('masa_pajak');
            $table->year('tahun_pajak');
            $table->decimal('nilai_dpp', 15, 2);
            $table->enum('perlakuan_ppn', ['non_ppn', 'ppn_11', 'ppn_12']);
            $table->enum('status_pembayaran', [
                'Belum Dibayar',
                'Siap Bayar (Approved)',
                'Sudah Dibayar & Dipotong'
            ]);
            $table->text('keterangan_pekerjaan')->nullable();
            $table->string('fasilitas_perpajakan', 100)->default('Tanpa Fasilitas (Tarif Normal)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
