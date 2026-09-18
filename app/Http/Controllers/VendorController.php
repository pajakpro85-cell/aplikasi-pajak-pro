<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Menampilkan daftar vendor.
     */
    public function index()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();

        return view('vendors.index', compact('vendors'));
    }

    /**
     * Menampilkan form tambah vendor.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Menyimpan data vendor baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'kategori_wp' => 'required|string|max:100',
            'tax_id_luar_negeri' => 'nullable|string|max:100',
            'negara_domisili' => 'nullable|string|max:100',
        ]);

        Vendor::create($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Data vendor berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail vendor.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load('invoices');

        return view('vendors.show', compact('vendor'));
    }

    /**
     * Menampilkan form edit vendor.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Memperbarui data vendor.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'kategori_wp' => 'required|string|max:100',
            'tax_id_luar_negeri' => 'nullable|string|max:100',
            'negara_domisili' => 'nullable|string|max:100',
        ]);

        $vendor->update($validated);

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Data vendor berhasil diperbarui.');
    }

    /**
     * Menghapus data vendor.
     */
    public function destroy(Vendor $vendor)
    {
        // Cek apakah vendor sudah digunakan pada invoice
        if ($vendor->invoices()->exists()) {
            return back()->with(
                'error',
                'Vendor tidak dapat dihapus karena sudah digunakan pada invoice.'
            );
        }

        $vendor->delete();

        return redirect()
            ->route('vendors.index')
            ->with('success', 'Data vendor berhasil dihapus.');
    }
}