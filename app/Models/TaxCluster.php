<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxCluster extends Model
{
    protected $table = 'tax_clusters';

    protected $primaryKey = 'id_cluster';

    public $timestamps = false;

    protected $fillable = [
        'nama_cluster',
        'dasar_regulasi',
    ];

    public function taxObjects(): HasMany
    {
        return $this->hasMany(TaxObject::class, 'tax_cluster_id', 'id_cluster');
    }
}
