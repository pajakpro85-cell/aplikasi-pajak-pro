<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TaxObjectController;
use App\Http\Controllers\EksporLaporanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProfilPerusahaanController;

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

Route::post('/simpan-tagihan', [InvoiceController::class, 'store'])
    ->name('tagihan.store');

Route::get('/daftar-tagihan', [InvoiceController::class, 'index'])->name('daftar-tagihan');
Route::get('/cetak-slip/{id}', fn ($id) => view('cetak-slip.index', compact('id')));

Route::get('/rekapitulasi', fn () => view('rekapitulasi.objek-pajak'));
// 2 route di bawah ini nanti dibuatkan viewnya setelah kamu kirim desainnya
Route::get('/rekapitulasi/klaster-pph', fn () => view('rekapitulasi.klaster-pph'));
Route::get('/rekapitulasi/lawan-transaksi', fn () => view('rekapitulasi.lawan-transaksi'));

Route::get('/ekspor-laporan', [EksporLaporanController::class, 'index'])->name('ekspor-laporan.index');
Route::get('/ekspor-laporan/cetak', [EksporLaporanController::class, 'cetak'])->name('ekspor-laporan.cetak');

Route::get('/asisten-pajak-ai', fn () => view('asisten-pajak-ai.index'));

Route::get('/pengaturan/pemotong-pajak', [ProfilPerusahaanController::class, 'index'])->name('pengaturan.pemotong-pajak');
Route::post('/pengaturan/pemotong-pajak', [ProfilPerusahaanController::class, 'store'])->name('pengaturan.pemotong-pajak.store');
Route::put('/pengaturan/pemotong-pajak/{profil}', [ProfilPerusahaanController::class, 'update'])->name('pengaturan.pemotong-pajak.update');
Route::delete('/pengaturan/pemotong-pajak/{profil}', [ProfilPerusahaanController::class, 'destroy'])->name('pengaturan.pemotong-pajak.destroy');

Route::get('/pengaturan/vendor', [VendorController::class, 'index'])->name('pengaturan.vendor');
Route::post('/pengaturan/vendor', [VendorController::class, 'store'])->name('pengaturan.vendor.store');
Route::put('/pengaturan/vendor/{vendor}', [VendorController::class, 'update'])->name('pengaturan.vendor.update');
Route::delete('/pengaturan/vendor/{vendor}', [VendorController::class, 'destroy'])->name('pengaturan.vendor.destroy');

Route::get('/pengaturan/master-kop', [TaxObjectController::class, 'index'])->name('master-kop.index');
Route::put('/pengaturan/master-kop/{taxObject}/tarif', [TaxObjectController::class, 'updateTarif'])->name('master-kop.update-tarif');
Route::post('/pengaturan/master-kop/reset', [TaxObjectController::class, 'resetDefault'])->name('master-kop.reset');