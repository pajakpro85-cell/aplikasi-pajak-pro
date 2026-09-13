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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id('id_journal');
            $table->foreignId('invoice_id')->constrained('invoices', 'id_invoice')->onDelete('cascade');
            $table->string('nomor_jurnal', 100);
            $table->date('tanggal_jurnal');
            $table->string('akun', 100);
            $table->string('keterangan', 255);
            $table->decimal('debit', 15, 2);
            $table->decimal('credit', 15, 2);
             $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
