<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tax_clusters')->insert([
            [
                'id_cluster' => 1,
                'nama_cluster' => 'PPh Pasal 23',
                'dasar_regulasi' => 'UU PPh Pasal 23 / PMK - 141/2015',
            ],
            [
                'id_cluster' => 2,
                'nama_cluster' => 'PPh Pasal 24 ayat (2)',
                'dasar_regulasi' => 'UU PPh Pasal 4 ayat (2) Final',
            ],
            [
                'id_cluster' => 3,
                'nama_cluster' => 'PPh Pasal 22',
                'dasar_regulasi' => 'UU PPh Pasal 22 / PMK - 34/2017',
            ],
            [
                'id_cluster' => 4,
                'nama_cluster' => 'PPh Pasal 15',
                'dasar_regulasi' => 'UU PPh Pasal 15 Norma Khusus',
            ],
            [
                'id_cluster' => 5,
                'nama_cluster' => 'PPh Pasal 26',
                'dasar_regulasi' => 'UU PPh Pasal 26 / P3B Tax Treaty',
            ],
        ]);
    }
}
