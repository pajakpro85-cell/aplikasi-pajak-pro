<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $primaryKey = 'id_invoice';

    public $timestamps = false;

    protected $fillable = [
        'vendor_id',
        'nomor_invoice',
        'tanggal_invoice',
        'masa_pajak',
        'tahun_pajak',
        'nilai_dpp',
        'perlakuan_ppn',
        'status_pembayaran',
        'keterangan_pekerjaan',
        'fasilitas_perpajakan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_invoice' => 'date',
            'nilai_dpp' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(
            Vendor::class,
            'vendor_id',
            'id_vendor'
        );
    }

    public function invoiceTaxes(): HasMany
    {
        return $this->hasMany(
        InvoiceTax::class,
        'invoice_id',
        'id_invoice'
        );
    }

    public function payments(): HasMany
    {
        return $this->hasMany(
        Payment::class,
        'invoice_id',
        'id_invoice'
        );
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(
        JournalEntry::class,
        'invoice_id',
        'id_invoice'
        );
    }
}