<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'invoice_id',
        'nilai_tagihan',
        'total_pph',
        'nilai_transfer',
        'tanggal_transfer',
        'status_pembayaran',
        'metode_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'nilai_tagihan' => 'decimal:2',
            'total_pph' => 'decimal:2',
            'nilai_transfer' => 'decimal:2',
            'tanggal_transfer' => 'date',
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
}