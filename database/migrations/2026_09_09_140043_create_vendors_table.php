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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('nama_vendor', 100);
            $table->string('npwp', 100)->nullable();
            $table->enum('kategori_wp', [
                'Badan Usaha (PT / CV / Firma)',
                'Orang Pribadi (Freelancer / Tenaga Ahli)',
                'Wajib Pajak Luar Negeri (WPLN)'
            ]);
            $table->string('tax_id_luar_negeri', 100)->nullable();
            $table->string('negara_domisili', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
