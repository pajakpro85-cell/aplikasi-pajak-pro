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
        Schema::create('tax_objects', function (Blueprint $table) {
            $table->id('id_object');
            $table->foreignId('tax_cluster_id')->constrained('tax_clusters', 'id_cluster')->onDelete('restrict');
            $table->string('kode_objek', 100);
            $table->string('nama_objek', 100);
            $table->text('deskripsi')->nullable();
            $table->string('dasar_hukum', 100);
            $table->decimal('tarif', 5, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_objects');
    }
};
