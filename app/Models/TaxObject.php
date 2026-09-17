<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxObject extends Model
{

    protected $primaryKey = 'id_object';

    public $timestamps = false;

    protected $fillable = [
        'tax_cluster_id',
        'kode_objek',
        'nama_objek',
        'deskripsi',
        'dasar_hukum',
        'tarif',
        'status',
    ];

    protected function  casts(): array
    {
        return [
            'tarif' => 'decimal:2',
        ];
    }
    public function taxCluster(): BelongsTo
    {
        return $this->belongsTo(TaxCluster::class, 'tax_cluster_id', 'id_cluster');
    }

    public function invoiceTaxes(): HasMany
{
    return $this->hasMany(
        InvoiceTax::class,
        'object_id',
        'id_object'
    );
}
}
