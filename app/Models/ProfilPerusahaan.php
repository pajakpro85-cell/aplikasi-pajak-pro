<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilPerusahaan extends Model
{
    protected $table = 'profil_perusahaan';

    protected $primaryKey = 'id_perusahaan';

    public $timestamps = false;

    protected $fillable = [
        'nama_perusahaan',
        'npwp',
        'alamat',
        'nama_pejabat',
        'jabatan',
        'metode_Default',
    ];
}
