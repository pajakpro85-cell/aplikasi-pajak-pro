<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $primaryKey = 'id_report';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'masa_pajak',
        'tahun_pajak',
        'jenis_laporan',
        'nama_file',
        'lokasi_file',
    ];

    protected function casts(): array
    {
        return [
            'tahun_pajak' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id_user'
        );
    }
}