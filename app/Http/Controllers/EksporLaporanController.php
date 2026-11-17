<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EksporLaporanController extends Controller
{
    private function getFormattedInvoices(?int $masa = null, ?int $tahun = null): array
    {
        $namaBulan = [
            1 => 'Januari (Masa 1)', 2 => 'Februari (Masa 2)', 3 => 'Maret (Masa 3)',
            4 => 'April (Masa 4)', 5 => 'Mei (Masa 5)', 6 => 'Juni (Masa 6)',
            7 => 'Juli (Masa 7)', 8 => 'Agustus (Masa 8)', 9 => 'September (Masa 9)',
            10 => 'Oktober (Masa 10)', 11 => 'November (Masa 11)', 12 => 'Desember (Masa 12)',
        ];

        $query = Invoice::with(['vendor', 'invoiceTaxes.taxObject.taxCluster'])
            ->orderBy('tanggal_invoice', 'asc');

        if ($masa) {
            $query->where('masa_pajak', $masa);
        }
        if ($tahun) {
            $query->where('tahun_pajak', $tahun);
        }

        return $query->get()->map(function ($inv) use ($namaBulan) {
            $dpp = (float) $inv->nilai_dpp;

            // Hitung PPN berdasarkan enum perlakuan_ppn
            $ppnRate = match ($inv->perlakuan_ppn) {
                'ppn_11' => 0.11,
                'ppn_12' => 0.12,
                default  => 0,
            };
            $ppn = round($dpp * $ppnRate, 2);

            // Ambil detail pajak dari relasi invoice_taxes
            $tax = $inv->invoiceTaxes->first();
            $potongan = $tax ? (float) $tax->nilai_pph : 0;
            $tarif = $tax ? (float) $tax->tarif : 0;
            $metode = $tax?->metode_beban ?? 'normal';

            // Bersih ditransfer: jika gross_up, PPh ditanggung pemotong
            $bersih = ($metode === 'gross_up')
                ? ($dpp + $ppn)
                : ($dpp + $ppn - $potongan);

            return [
                'id'              => $inv->id_invoice,
                'noInvoice'       => $inv->nomor_invoice,
                'tanggalISO'      => $inv->tanggal_invoice?->format('Y-m-d') ?? '-',
                'masaPajak'       => (int) $inv->masa_pajak,
                'masaBulan'       => $namaBulan[(int) $inv->masa_pajak] ?? '',
                'tahun'           => (int) $inv->tahun_pajak,
                'klien'           => $inv->vendor?->nama_vendor ?? '-',
                'npwp'            => $inv->vendor?->npwp ?? $inv->vendor?->tax_id_luar_negeri ?? '-',
                'jenisPphDisplay' => $tax?->taxObject?->taxCluster?->nama_cluster ?? '-',
                'kopCode'         => $tax?->taxObject?->kode_objek ?? '-',
                'dppRaw'          => $dpp,
                'ppnRaw'          => $ppn,
                'tarifDisplay'    => $tarif . '%',
                'potonganRaw'     => $potongan,
                'bersihRaw'       => $bersih,
                'status'          => $inv->status_pembayaran,
            ];
        })->values()->toArray();
    }

    public function index()
    {
        $invoices = $this->getFormattedInvoices();

        return view('ekspor-laporan.index', compact('invoices'));
    }

    public function cetak(Request $request)
    {
        // Parsing angka bulan dari parameter "November (Masa 11)" -> 11
        $masaParam = $request->query('masa', '');
        preg_match('/Masa\s+(\d+)/i', $masaParam, $matches);
        $masaAngka = isset($matches[1]) ? (int) $matches[1] : null;
        $tahun = (int) $request->query('tahun', date('Y'));

        $invoices = $this->getFormattedInvoices($masaAngka, $tahun);

        // Ambil profil perusahaan pemotong pajak dari tabel profil_perusahaan
        $profil = DB::table('profil_perusahaan')->first();
        $buyer = [
            'name' => $profil->nama_perusahaan ?? $profil->nama_wp ?? 'PT Pemotong Pajak',
            'npwp' => $profil->npwp ?? '-',
        ];

        return view('ekspor-laporan.cetak', compact('invoices', 'buyer'));
    }
}