<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class ProfilPerusahaanController extends Controller
{
    public function index()
    {
        $profil = ProfilPerusahaan::first();

        return view('profil_perusahaan.index', compact('profil'));
    }

    public function edit()
    {
        $profil = ProfilPerusahaan::first();

        return view('profil_perusahaan.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
            'nama_pejabat' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:100',
            'metode_Default' => 'nullable|string|max:50',
        ]);

        $profil = ProfilPerusahaan::first();

        if ($profil) {
            $profil->update($validated);
        } else {
            ProfilPerusahaan::create($validated);
        }

        return redirect()
            ->route('profil-perusahaan.index')
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}