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
     * Memproses login Admin.
     */
    public function login(Request $request)
    {
        // Validasi input login
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek email dan password
        if (Auth::attempt($credentials)) {

            // Membuat ulang session setelah login berhasil
            $request->session()->regenerate();

            // Mengarahkan Admin ke dashboard
            return redirect()->route('dashboard');
        }

        // Jika login gagal
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->withInput();
    }

    /**
     * Memproses logout Admin.
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