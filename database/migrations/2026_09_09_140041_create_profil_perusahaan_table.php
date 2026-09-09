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
        Schema::create('profil_perusahaan', function (Blueprint $table) {
            $table->id('id_perusahaan');
            $table->string('nama_perusahaan', 100);
            $table->string('npwp', 100);
            $table->text('alamat');
            $table->string('nama_pejabat', 100);
            $table->string('jabatan', 100);
            $table->enum('metode_default', ['normal', 'gross_up']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_perusahaan');
    }
};
