<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendors')->insert([
            [
                'id_vendor' => 1,
                'nama_vendor' => 'PT Graha Pratama Solusindo',
                'npwp' => '02.456.789.1-013.000',
                'kategori_wp' => 'Badan Usaha (PT / CV / Firma)',
                'tax_id_luar_negeri' => null,
                'negara_domisili' => null,
            ],
            [
                'id_vendor' => 2,
                'nama_vendor' => 'PT Cipta Sarana Bangun Indonesia',
                'npwp' => '03.112.233.4-021.000',
                'kategori_wp' => 'Badan Usaha (PT / CV / Firma)',
                'tax_id_luar_negeri' => null,
                'negara_domisili' => null,
            ],
            [
                'id_vendor' => 3,
                'nama_vendor' => 'PT Samudera Bahtera Nusantara',
                'npwp' => '01.998.877.6-044.000',
                'kategori_wp' => 'Badan Usaha (PT / CV / Firma)',
                'tax_id_luar_negeri' => null,
                'negara_domisili' => null,
            ],
            [
                'id_vendor' => 4,
                'nama_vendor' => 'CloudScale Technologies Pte.Ltd',
                'npwp' => null,
                'kategori_wp' => 'Wajib Pajak Luar Negeri (WPLN)',
                'tax_id_luar_negeri' => 'TAXID-SG-201844919',
                'negara_domisili' => 'Singapore (SG)',
            ],
            [
                'id_vendor' => 5,
                'nama_vendor' => 'CV Mitra Logistik Sejahtera',
                'npwp' => '07.334.556.7-081.000',
                'kategori_wp' => 'Badan Usaha (PT / CV / Firma)',
                'tax_id_luar_negeri' => null,
                'negara_domisili' => null,
            ],
        ]);
    }
}
