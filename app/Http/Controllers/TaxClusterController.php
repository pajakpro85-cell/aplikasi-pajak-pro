<?php

namespace App\Http\Controllers;

use App\Models\TaxCluster;
use Illuminate\Http\Request;

class TaxClusterController extends Controller
{
    /**
     * Menampilkan daftar cluster pajak.
     */
    public function index()
    {
        $taxClusters = TaxCluster::withCount('taxObjects')
            ->orderBy('nama_cluster')
            ->get();

        return view('tax_clusters.index', compact('taxClusters'));
    }

    /**
     * Menampilkan form tambah cluster pajak.
     */
    public function create()
    {
        return view('tax_clusters.create');
    }

    /**
     * Menyimpan cluster pajak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_cluster' => 'required|string|max:255',
            'dasar_regulasi' => 'required|string|max:255',
        ]);

        TaxCluster::create($validated);

        return redirect()
            ->route('tax-clusters.index')
            ->with('success', 'Cluster pajak berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail cluster pajak.
     */
    public function show(TaxCluster $taxCluster)
    {
        $taxCluster->load('taxObjects');

        return view('tax_clusters.show', compact('taxCluster'));
    }

    /**
     * Menampilkan form edit cluster pajak.
     */
    public function edit(TaxCluster $taxCluster)
    {
        return view('tax_clusters.edit', compact('taxCluster'));
    }

    /**
     * Memperbarui data cluster pajak.
     */
    public function update(Request $request, TaxCluster $taxCluster)
    {
        $validated = $request->validate([
            'nama_cluster' => 'required|string|max:255',
            'dasar_regulasi' => 'required|string|max:255',
        ]);

        $taxCluster->update($validated);

        return redirect()
            ->route('tax-clusters.index')
            ->with('success', 'Cluster pajak berhasil diperbarui.');
    }

    /**
     * Menghapus cluster pajak.
     */
    public function destroy(TaxCluster $taxCluster)
    {
        // Cek apakah cluster masih memiliki objek pajak
        if ($taxCluster->taxObjects()->exists()) {
            return back()->with(
                'error',
                'Cluster pajak tidak dapat dihapus karena masih memiliki objek pajak.'
            );
        }

        $taxCluster->delete();

        return redirect()
            ->route('tax-clusters.index')
            ->with('success', 'Cluster pajak berhasil dihapus.');
    }
}