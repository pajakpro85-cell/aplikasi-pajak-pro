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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('id_payment');
            $table->foreignId('invoice_id')->constrained('invoices', 'id_invoice')->onDelete('cascade');
            $table->decimal('nilai_tagihan', 18, 2);
            $table->decimal('total_pph', 18, 2);
            $table->decimal('nilai_transfer', 15, 2);
            $table->date('tanggal_transfer');
            $table->enum('status_pembayaran', [
                'Belum Dibayar',
                'Siap Bayar (Approved)',
                'Sudah Dibayar & Dipotong'
            ]);
            $table->enum('metode_pembayaran', ['transfer', 'cash', 'qris']);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
