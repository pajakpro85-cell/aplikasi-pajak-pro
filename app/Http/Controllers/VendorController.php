<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    private function formatVendor($v): array
    {
        $isWpln = str_contains($v->kategori_wp ?? '', 'WPLN');

        return [
            'id'         => $v->id_vendor,
            'nama'       => $v->nama_vendor,
            'npwp'       => $isWpln ? ($v->tax_id_luar_negeri ?: $v->npwp) : ($v->npwp ?: $v->tax_id_luar_negeri),
            'npwpRaw'    => $v->npwp ?? '',
            'taxIdRaw'   => $v->tax_id_luar_negeri ?? '',
            'kategoriWp' => $v->kategori_wp ?? 'WP Badan Dalam Negeri',
            'negara'     => $v->negara_domisili ?? 'Indonesia',
        ];
    }

    public function index()
    {
        $vendors = Vendor::orderBy('id_vendor', 'desc')
            ->get()
            ->map(fn ($v) => $this->formatVendor($v))
            ->values();

        return view('pengaturan.vendor', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor'        => 'required|string|max:255',
            'npwp'               => 'nullable|string|max:50',
            'kategori_wp'        => 'required|string|max:100',
            'tax_id_luar_negeri' => 'nullable|string|max:100',
            'negara_domisili'    => 'nullable|string|max:100',
        ]);

        $vendor = Vendor::create($validated);

        return response()->json([
            'message' => 'Data vendor berhasil ditambahkan.',
            'data'    => $this->formatVendor($vendor),
        ]);
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'nama_vendor'        => 'required|string|max:255',
            'npwp'               => 'nullable|string|max:50',
            'kategori_wp'        => 'required|string|max:100',
            'tax_id_luar_negeri' => 'nullable|string|max:100',
            'negara_domisili'    => 'nullable|string|max:100',
        ]);

        $vendor->update($validated);

        return response()->json([
            'message' => 'Data vendor berhasil diperbarui.',
            'data'    => $this->formatVendor($vendor->fresh()),
        ]);
    }

    public function destroy(Vendor $vendor)
    {
        if ($vendor->invoices()->exists()) {
            return response()->json([
                'message' => 'Vendor tidak dapat dihapus karena sudah digunakan pada transaksi invoice.',
            ], 422);
        }

        $vendor->delete();

        return response()->json([
            'message' => 'Data vendor berhasil dihapus.',
        ]);
    }
}