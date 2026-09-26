<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

Route::get('/hitung-tagihan', function () {
    return view('hitung-tagihan.index');
})->name('hitung-tagihan');

Route::get('/daftar-tagihan', fn () => view('daftar-tagihan.index'));
Route::get('/cetak-slip/{id}', fn ($id) => view('cetak-slip.index', compact('id')));

Route::get('/rekapitulasi', fn () => view('rekapitulasi.objek-pajak'));
// 2 route di bawah ini nanti dibuatkan viewnya setelah kamu kirim desainnya
Route::get('/rekapitulasi/klaster-pph', fn () => view('rekapitulasi.klaster-pph'));
Route::get('/rekapitulasi/lawan-transaksi', fn () => view('rekapitulasi.lawan-transaksi'));

Route::get('/ekspor-laporan', fn () => view('ekspor-laporan.index'));
Route::get('/asisten-pajak-ai', fn () => view('asisten-pajak-ai.index'));
Route::get('/pengaturan/pemotong-pajak', fn () => view('pengaturan.pemotong-pajak'));
Route::get('/pengaturan/vendor', fn () => view('pengaturan.vendor'));
Route::get('/pengaturan/master-kop', fn () => view('pengaturan.master-kop'));