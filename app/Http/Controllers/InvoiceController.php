<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Vendor;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Menampilkan daftar invoice.
     */
    public function index()
    {
        $invoices = Invoice::with('vendor')
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Menampilkan form tambah invoice.
     */
    public function create()
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();

        return view('invoices.create', compact('vendors'));
    }

    /**
     * Menyimpan invoice baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id_vendor',
            'nomor_invoice' => 'required|string|max:100',
            'tanggal_invoice' => 'required|date',
            'masa_pajak' => 'required|string|max:20',
            'tahun_pajak' => 'required|integer|min:2000|max:2100',
            'nilai_dpp' => 'required|numeric|min:0',
            'perlakuan_ppn' => 'nullable|string|max:100',
            'status_pembayaran' => 'required|string|max:50',
            'keterangan_pekerjaan' => 'nullable|string',
            'fasilitas_perpajakan' => 'nullable|string|max:255',
        ]);

        Invoice::create($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'vendor',
            'invoiceTaxes.taxObject',
            'payments',
            'journalEntries',
        ]);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Menampilkan form edit invoice.
     */
    public function edit(Invoice $invoice)
    {
        $vendors = Vendor::orderBy('nama_vendor')->get();

        return view('invoices.edit', compact(
            'invoice',
            'vendors'
        ));
    }

    /**
     * Memperbarui data invoice.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id_vendor',
            'nomor_invoice' => 'required|string|max:100',
            'tanggal_invoice' => 'required|date',
            'masa_pajak' => 'required|string|max:20',
            'tahun_pajak' => 'required|integer|min:2000|max:2100',
            'nilai_dpp' => 'required|numeric|min:0',
            'perlakuan_ppn' => 'nullable|string|max:100',
            'status_pembayaran' => 'required|string|max:50',
            'keterangan_pekerjaan' => 'nullable|string',
            'fasilitas_perpajakan' => 'nullable|string|max:255',
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    /**
     * Menghapus invoice.
     */
    public function destroy(Invoice $invoice)
    {
        // Cek apakah invoice sudah memiliki data pajak
        if ($invoice->invoiceTaxes()->exists()) {
            return back()->with(
                'error',
                'Invoice tidak dapat dihapus karena sudah memiliki data pajak.'
            );
        }

        // Cek apakah invoice sudah memiliki pembayaran
        if ($invoice->payments()->exists()) {
            return back()->with(
                'error',
                'Invoice tidak dapat dihapus karena sudah memiliki data pembayaran.'
            );
        }

        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }
}