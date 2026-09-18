<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Vendor;
use App\Models\TaxObject;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard PajakPro.
     */
    public function index()
    {
        // Menghitung jumlah invoice
        $jumlahInvoice = Invoice::count();

        // Menghitung jumlah vendor
        $jumlahVendor = Vendor::count();

        // Menghitung jumlah objek pajak yang aktif
        $jumlahObjekPajak = TaxObject::where('status', 'aktif')->count();

        // Menghitung jumlah pembayaran
        $jumlahPembayaran = Payment::count();

        // Mengirim data ke halaman dashboard
        return view('dashboard', compact(
            'jumlahInvoice',
            'jumlahVendor',
            'jumlahObjekPajak',
            'jumlahPembayaran'
        ));
    }
}