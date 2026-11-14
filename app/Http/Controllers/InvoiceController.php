<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Vendor;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan daftar invoice.
     */
    public function index()
    {
        $invoices = Invoice::with('vendor')
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view('daftar-tagihan.index', compact('invoices'));
    }

    /**
     * Menampilkan form tambah invoice.
     */
    public function create()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();

        return view('invoices.create', compact('vendors'));
    }

    /**
 * Menyimpan invoice baru.
 */
/**
     * Menyimpan invoice baru dari form kalkulator.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form frontend
        $validated = $request->validate([
            'vendor_id'            => 'nullable',
            'nama_klien'           => 'nullable|string|max:255',
            'npwp_klien'           => 'nullable|string|max:50',
            'kategori_wp'          => 'nullable|string|max:100',
            'nomor_invoice'        => 'required|string|max:100',
            'tanggal_invoice'      => 'required|date',
            'masa_pajak'           => 'required',
            'tahun_pajak'          => 'required|integer|min:2000|max:2100',
            'nilai_dpp'            => 'required|numeric|min:0',
            'perlakuan_ppn'        => 'nullable|string|max:100',
            'klaster_pph'          => 'nullable|string|max:50',
            'kode_objek_pajak'     => 'nullable|string|max:50',
            'tarif'                => 'nullable|numeric',
            'nilai_pph'            => 'nullable|numeric',
            'status_pembayaran'    => 'required|string|max:50',
            'keterangan_pekerjaan' => 'nullable|string',
            'fasilitas_perpajakan' => 'nullable|string|max:255',
        ]);

        // 2. Tangkap & Kelola Vendor secara Dinamis (Mencegah salah masuk ke vendor default)
        $namaKlien  = $request->input('nama_klien');
        $npwpKlien  = $request->input('npwp_klien');
        $kategoriWp = $request->input('kategori_wp');

        if ($request->filled('vendor_id') && is_numeric($request->vendor_id)) {
            $validated['vendor_id'] = $request->vendor_id;
        } elseif (!empty($namaKlien)) {
            $vendor = Vendor::firstOrCreate(
                ['nama_vendor' => $namaKlien],
                [
                    'npwp'        => $npwpKlien ?? '-',
                    'kategori_wp' => $kategoriWp ?? 'Badan Usaha',
                ]
            );
            $validated['vendor_id'] = $vendor->id_vendor ?? $vendor->id;
        } else {
            // Fallback aman jika nama klien kosong
            $defaultVendor = Vendor::first();
            $validated['vendor_id'] = $defaultVendor ? ($defaultVendor->id_vendor ?? $defaultVendor->id) : 1;
        }

        // 3. Ekstrak angka bulan dari teks masa_pajak (contoh: "Oktober (Masa 10)" atau "10" menjadi angka 10)
        if (isset($validated['masa_pajak'])) {
            if (is_numeric($validated['masa_pajak'])) {
                $validated['masa_pajak'] = (int) $validated['masa_pajak'];
            } else {
                preg_match('/\d+/', $validated['masa_pajak'], $matches);
                $validated['masa_pajak'] = isset($matches[0]) ? (int) $matches[0] : 1;
            }
        }

        // 4. Mapping perlakuan PPN ke format ENUM database
        if (isset($validated['perlakuan_ppn'])) {
            $ppnInput = $validated['perlakuan_ppn'];
            if (str_contains($ppnInput, '11')) {
                $validated['perlakuan_ppn'] = 'ppn_11';
            } elseif (str_contains($ppnInput, '12')) {
                $validated['perlakuan_ppn'] = 'ppn_12';
            } else {
                $validated['perlakuan_ppn'] = 'non_ppn';
            }
        }

        // 5. Simpan data lengkap ke database
        Invoice::create($validated);

        // 6. Kembalikan respons JSON agar AJAX di JavaScript sukses menangkapnya dan berpindah halaman
        return response()->json([
            'success'  => true,
            'message'  => 'Invoice berhasil disimpan.',
            'redirect' => route('daftar-tagihan')
        ]);
    }
    /**
     * Menampilkan detail invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'vendor',
            'invoiceTaxes.taxObject',
            'payments',
            'journalEntries',
        ]);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Menampilkan form edit invoice.
     */
    public function edit(Invoice $invoice)
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();

        return view('invoices.edit', compact(
            'invoice',
            'vendors'
        ));
    }

    /**
     * Memperbarui data invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id_vendor',
            'nomor_invoice' => 'required|string|max:100',
            'tanggal_invoice' => 'required|date',
            'masa_pajak' => 'required|string|max:20',
            'tahun_pajak' => 'required|integer|min:2000|max:2100',
            'nilai_dpp' => 'required|numeric|min:0',
            'perlakuan_ppn' => 'nullable|string|max:100',
            'status_pembayaran' => 'required|string|max:50',
            'keterangan_pekerjaan' => 'nullable|string',
            'fasilitas_perpajakan' => 'nullable|string|max:255',
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    /**
     * Menghapus invoice.
     */
    public function destroy(Invoice $invoice)
    {
        // Cek apakah invoice sudah memiliki data pajak
        if ($invoice->invoiceTaxes()->exists()) {
            return back()->with(
                'error',
                'Invoice tidak dapat dihapus karena sudah memiliki data pajak.'
            );
        }

        // Cek apakah invoice sudah memiliki pembayaran
        if ($invoice->payments()->exists()) {
            return back()->with(
                'error',
                'Invoice tidak dapat dihapus karena sudah memiliki data pembayaran.'
            );
        }

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}