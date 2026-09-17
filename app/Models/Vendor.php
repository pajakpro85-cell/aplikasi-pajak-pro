<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{

    protected $primaryKey = 'id_vendor';

    public $timestamps = false;

    protected $fillable = [
        'nama_vendor',
        'npwp',
        'kategori_wp',
        'tax_id_luar_negeri',
        'negara_domisili',
    ];

    public function invoices(): HasMany
{
    return $this->hasMany(
        Invoice::class,
        'vendor_id',
        'id_vendor'
    );
}
}
