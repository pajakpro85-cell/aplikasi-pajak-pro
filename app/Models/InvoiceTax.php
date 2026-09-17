<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceTax extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'object_id',
        'tarif',
        'nilai_pph',
        'metode_beban',
    ];

    protected function casts(): array
    {
        return [
            'tarif' => 'decimal:2',
            'nilai_pph' => 'decimal:2',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id',
            'id_invoice'
        );
    }

    public function taxObject(): BelongsTo
    {
        return $this->belongsTo(
            TaxObject::class,
            'object_id',
            'id_object'
        );
    }
}