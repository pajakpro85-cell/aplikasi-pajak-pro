<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $primaryKey = 'id_journal';

    protected $fillable = [
        'invoice_id',
        'nomor_jurnal',
        'tanggal_jurnal',
        'akun',
        'keterangan',
        'debit',
        'credit',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_jurnal' => 'date',
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
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