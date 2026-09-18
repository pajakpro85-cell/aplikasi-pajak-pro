<?php

namespace App\Http\Controllers;

use App\Models\TaxCluster;
use App\Models\TaxObject;
use Illuminate\Http\Request;

class TaxObjectController extends Controller
{
    /**
     * Menampilkan daftar objek pajak.
     */
    public function index()
    {
        $taxObjects = TaxObject::with('taxCluster')
            ->orderBy('kode_objek')
            ->get();

        return view('tax_objects.index', compact('taxObjects'));
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
}