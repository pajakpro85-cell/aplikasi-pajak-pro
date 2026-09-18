<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran.
     */
    public function index()
    {
        $payments = Payment::with('invoice.vendor')
            ->orderByDesc('tanggal_transfer')
            ->get();

        return view('payments.index', compact('payments'));
    }

    /**
     * Menampilkan form pembayaran untuk invoice.
     */
    public function create(Invoice $invoice)
    {
        // Menghitung total PPh dari pajak yang ada pada invoice
        $totalPph = $invoice->invoiceTaxes()->sum('nilai_pph');

        // Nilai yang dibayarkan setelah dikurangi PPh
        $nilaiTransfer = $invoice->nilai_dpp - $totalPph;

        return view('payments.create', compact(
            'invoice',
            'totalPph',
            'nilaiTransfer'
        ));
    }

    /**
     * Menyimpan data pembayaran.
     */
    public function store(Request $request, Invoice $invoice)
    {
        // Menghitung total PPh berdasarkan data pajak invoice
        $totalPph = $invoice->invoiceTaxes()->sum('nilai_pph');

        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'status_pembayaran' => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        // Nilai tagihan berasal dari DPP invoice
        $nilaiTagihan = $invoice->nilai_dpp;

        // Nilai yang ditransfer setelah dikurangi PPh
        $nilaiTransfer = $nilaiTagihan - $totalPph;

        Payment::create([
            'invoice_id' => $invoice->id_invoice,
            'nilai_tagihan' => $nilaiTagihan,
            'total_pph' => $totalPph,
            'nilai_transfer' => $nilaiTransfer,
            'tanggal_transfer' => $validated['tanggal_transfer'],
            'status_pembayaran' => $validated['status_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
        ]);

        // Memperbarui status pembayaran pada invoice
        $invoice->update([
            'status_pembayaran' => $validated['status_pembayaran'],
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Data pembayaran berhasil disimpan.');
    }

    /**
     * Menampilkan detail pembayaran.
     */
    public function show(Payment $payment)
    {
        $payment->load('invoice.vendor');

        return view('payments.show', compact('payment'));
    }

    /**
     * Menampilkan form edit pembayaran.
     */
    public function edit(Payment $payment)
    {
        $payment->load('invoice.vendor');

        return view('payments.edit', compact('payment'));
    }

    /**
     * Memperbarui data pembayaran.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'tanggal_transfer' => 'required|date',
            'status_pembayaran' => 'required|string|max:50',
            'metode_pembayaran' => 'required|string|max:50',
        ]);

        // Ambil invoice yang berkaitan dengan pembayaran
        $invoice = $payment->invoice;

        // Hitung ulang total PPh
        $totalPph = $invoice->invoiceTaxes()->sum('nilai_pph');

        // Hitung ulang nilai tagihan dan nilai transfer
        $nilaiTagihan = $invoice->nilai_dpp;
        $nilaiTransfer = $nilaiTagihan - $totalPph;

        $payment->update([
            'nilai_tagihan' => $nilaiTagihan,
            'total_pph' => $totalPph,
            'nilai_transfer' => $nilaiTransfer,
            'tanggal_transfer' => $validated['tanggal_transfer'],
            'status_pembayaran' => $validated['status_pembayaran'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
        ]);

        // Sinkronisasi status pembayaran pada invoice
        $invoice->update([
            'status_pembayaran' => $validated['status_pembayaran'],
        ]);

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    /**
     * Menghapus data pembayaran.
     */
    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;

        $payment->delete();

        // Mengembalikan status invoice
        if ($invoice) {
            $invoice->update([
                'status_pembayaran' => 'Belum Bayar',
            ]);
        }

        return redirect()
            ->route('payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus.');
    }
}