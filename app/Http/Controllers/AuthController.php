<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses login.
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek username dan password
        if (Auth::attempt($credentials)) {

            // Membuat ulang session setelah login berhasil
            $request->session()->regenerate();

            // Mengarahkan user ke dashboard
            return redirect()->route('dashboard');
        }

        // Jika login gagal
        return back()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ])
            ->withInput();
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        // Mengeluarkan user dari sistem
        Auth::logout();

        // Menghapus session login
        $request->session()->invalidate();

        // Membuat token CSRF baru
        $request->session()->regenerateToken();

        // Kembali ke halaman login
        return redirect()->route('login');
    }
}