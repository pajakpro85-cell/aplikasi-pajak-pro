<?php

namespace App\Http\Controllers;

use App\Models\TaxCluster;
use App\Models\TaxObject;
use Illuminate\Http\Request;
use Psy\Command\WhereamiCommand;    

class TaxObjectController extends Controller
{
    /**
     * Menampilkan daftar objek pajak.
     */
    public function index()
    {
        $taxObjects = TaxObject::with('taxCluster')
            ->where('status', 'aktif')
            ->orderBy('id_object')
            ->get();

        // Ambil daftar nama klaster unik dari database untuk opsi dropdown filter
        $clusters = TaxCluster::pluck('nama_cluster')->unique()->values();

        return view('pengaturan.master-kop', compact('taxObjects', 'clusters'));
    }

    /**
     * Menampilkan form tambah objek pajak.
     */
    public function create()
    {
        $taxClusters = TaxCluster::orderBy('nama_cluster')->get();

        return view('tax_objects.create', compact('taxClusters'));
    }

    /**
     * Menyimpan objek pajak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tax_cluster_id' => 'required|exists:tax_clusters,id_cluster',
            'kode_objek' => 'required|string|max:50',
            'nama_objek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'dasar_hukum' => 'nullable|string|max:255',
            'tarif' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        TaxObject::create($validated);

        return redirect()
            ->route('tax-objects.index')
            ->with('success', 'Objek pajak berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail objek pajak.
     */
    public function show(TaxObject $taxObject)
    {
        $taxObject->load('taxCluster');

        return view('tax_objects.show', compact('taxObject'));
    }

    /**
     * Menampilkan form edit objek pajak.
     */
    public function edit(TaxObject $taxObject)
    {
        $taxClusters = TaxCluster::orderBy('nama_cluster')->get();

        return view('tax_objects.edit', compact(
            'taxObject',
            'taxClusters'
        ));
    }

    /**
     * Memperbarui data objek pajak.
     */
    public function update(Request $request, TaxObject $taxObject)
    {
        $validated = $request->validate([
            'tax_cluster_id' => 'required|exists:tax_clusters,id_cluster',
            'kode_objek' => 'required|string|max:50',
            'nama_objek' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'dasar_hukum' => 'nullable|string|max:255',
            'tarif' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $taxObject->update($validated);

        return redirect()
            ->route('tax-objects.index')
            ->with('success', 'Objek pajak berhasil diperbarui.');
    }

    /**
     * Menghapus objek pajak.
     */
    public function destroy(TaxObject $taxObject)
    {
        // Cek apakah objek pajak sudah digunakan pada invoice
        if ($taxObject->invoiceTaxes()->exists()) {
            return back()->with(
                'error',
                'Objek pajak tidak dapat dihapus karena sudah digunakan pada invoice.'
            );
        }

        $taxObject->delete();

        return redirect()
            ->route('tax-objects.index')
            ->with('success', 'Objek pajak berhasil dihapus.');
    }

    public function updateTarif(Request $request, TaxObject $taxObject)
    {
        $validated = $request->validate([
            'tarif' => 'required|numeric|min:0|max:100',
        ]);

        $taxObject->update([
            'tarif' => $validated['tarif'],
        ]);

        return response()->json([
            'message' => 'Tarif berhasil diperbarui.',
            'data'    => $taxObject,
        ]);
    }

    public function resetDefault()
    {
        // Karena tabel tax_objects tidak punya kolom tarif_default terpisah,
        // nilai default diambil dari tarif standar klaster / aturan PER-24/PJ/2021
        $objects = TaxObject::with('taxCluster')->get();

        foreach ($objects as $obj) {
            // Sesuaikan tarif standar per kode/klaster jika berbeda (misal PPh 26 = 20%, Sewa Tanah = 10%, dst.)
            $defaultTarif = match (true) {
                str_starts_with($obj->kode_objek, '27-') => 20,    // PPh 26
                str_starts_with($obj->kode_objek, '28-402') => 10, // Sewa Tanah/Bangunan PPh 4(2)
                str_starts_with($obj->kode_objek, '28-403') => 2.5,// Pengalihan Tanah/Bangunan
                str_starts_with($obj->kode_objek, '28-401') => 20, // Bunga Deposito
                str_starts_with($obj->kode_objek, '28-419') => 10, // Dividen WP OP
                str_starts_with($obj->kode_objek, '22-') => 1.5,   // PPh 22
                default => 2,                                      // Standar PPh 23 & Jasa Konstruksi
            };

            $obj->update(['tarif' => $defaultTarif]);
        }

        $updated = TaxObject::with('taxCluster')->where('status', 'aktif')->orderBy('id_object')->get();

        return response()->json([
            'message' => 'Semua tarif berhasil di-reset ke standar.',
            'data'    => $updated,
        ]);
    }
}