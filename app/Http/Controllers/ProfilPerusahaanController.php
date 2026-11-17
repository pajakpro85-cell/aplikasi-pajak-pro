<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class ProfilPerusahaanController extends Controller
{
    private function formatProfil($p): array
    {
        return [
            'id'             => $p->id_perusahaan,
            'namaPerusahaan' => $p->nama_perusahaan ?? '',
            'npwp'           => $p->npwp ?? '',
            'alamat'         => $p->alamat ?? '',
            'namaPejabat'    => $p->nama_pejabat ?? '',
            'jabatan'        => $p->jabatan ?? '',
            'metodeDefault'  => $p->metode_default ?? 'Normal',
        ];
    }

    public function index()
    {
        $profiles = ProfilPerusahaan::orderBy('id_perusahaan', 'desc')
            ->get()
            ->map(fn ($p) => $this->formatProfil($p->id))
            ->values();

        return view('pengaturan.pemotong-pajak', compact('profiles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp'            => 'nullable|string|max:50',
            'alamat'          => 'nullable|string',
            'nama_pejabat'    => 'nullable|string|max:255',
            'jabatan'         => 'nullable|string|max:100',
            'metode_default'  => 'required|string|max:50',
        ]);

        $profil = ProfilPerusahaan::create($validated);

        return response()->json([
            'message' => 'Profil pemotong pajak berhasil ditambahkan.',
            'data'    => $this->formatProfil($profil),
        ]);
    }

    public function update(Request $request, ProfilPerusahaan $profil)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp'            => 'nullable|string|max:50',
            'alamat'          => 'nullable|string',
            'nama_pejabat'    => 'nullable|string|max:255',
            'jabatan'         => 'nullable|string|max:100',
            'metode_default'  => 'required|string|max:50',
        ]);

        $profil->update($validated);

        return response()->json([
            'message' => 'Profil pemotong pajak berhasil diperbarui.',
            'data'    => $this->formatProfil($profil->fresh()),
        ]);
    }

    public function destroy(ProfilPerusahaan $profil)
    {
        $profil->delete();

        return response()->json([
            'message' => 'Profil pemotong pajak berhasil dihapus.',
        ]);
    }
}