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
       Schema::create('invoice_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices', 'id_invoice')->onDelete('cascade');
            $table->foreignId('object_id')->constrained('tax_objects', 'id_object')->onDelete('restrict');
            $table->decimal('tarif', 5, 2);
            $table->decimal('nilai_pph', 15, 2);
            $table->enum('metode_beban', ['normal', 'gross_up']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_taxes');
    }
};
