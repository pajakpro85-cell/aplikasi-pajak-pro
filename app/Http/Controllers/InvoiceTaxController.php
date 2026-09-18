<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceTax;
use App\Models\TaxObject;
use Illuminate\Http\Request;

class InvoiceTaxController extends Controller
{
    /**
     * Menampilkan daftar pajak pada sebuah invoice.
     */
    public function index(Invoice $invoice)
    {
        $invoice->load('vendor');

        $invoiceTaxes = $invoice->invoiceTaxes()
            ->with('taxObject.taxCluster')
            ->get();

        return view('invoice_taxes.index', compact(
            'invoice',
            'invoiceTaxes'
        ));
    }

    /**
     * Menampilkan form tambah pajak pada invoice.
     */
    public function create(Invoice $invoice)
    {
        $taxObjects = TaxObject::with('taxCluster')
            ->where('status', 'aktif')
            ->orderBy('kode_objek')
            ->get();

        return view('invoice_taxes.create', compact(
            'invoice',
            'taxObjects'
        ));
    }

    /**
     * Menyimpan data pajak pada invoice.
     */
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'object_id' => 'required|exists:tax_objects,id_object',
            'metode_beban' => 'required|string|max:50',
        ]);

        $taxObject = TaxObject::findOrFail(
            $validated['object_id']
        );

        // Mengambil tarif dari master objek pajak
        $tarif = $taxObject->tarif;

        // Menghitung nilai PPh
        $nilaiPph = $invoice->nilai_dpp * ($tarif / 100);

        InvoiceTax::create([
            'invoice_id' => $invoice->id_invoice,
            'object_id' => $taxObject->id_object,
            'tarif' => $tarif,
            'nilai_pph' => $nilaiPph,
            'metode_beban' => $validated['metode_beban'],
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Data pajak berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit data pajak.
     */
    public function edit(InvoiceTax $invoiceTax)
    {
        $taxObjects = TaxObject::with('taxCluster')
            ->where('status', 'aktif')
            ->orderBy('kode_objek')
            ->get();

        return view('invoice_taxes.edit', compact(
            'invoiceTax',
            'taxObjects'
        ));
    }

    /**
     * Memperbarui data pajak pada invoice.
     */
    public function update(
        Request $request,
        InvoiceTax $invoiceTax
    ) {
        $validated = $request->validate([
            'object_id' => 'required|exists:tax_objects,id_object',
            'metode_beban' => 'required|string|max:50',
        ]);

        $taxObject = TaxObject::findOrFail(
            $validated['object_id']
        );

        // Mengambil tarif terbaru dari master objek pajak
        $tarif = $taxObject->tarif;

        // Menghitung ulang nilai PPh
        $nilaiPph = $invoiceTax->invoice->nilai_dpp
            * ($tarif / 100);

        $invoiceTax->update([
            'object_id' => $taxObject->id_object,
            'tarif' => $tarif,
            'nilai_pph' => $nilaiPph,
            'metode_beban' => $validated['metode_beban'],
        ]);

        return redirect()
            ->route('invoices.show', $invoiceTax->invoice_id)
            ->with('success', 'Data pajak berhasil diperbarui.');
    }

    /**
     * Menghapus data pajak dari invoice.
     */
    public function destroy(InvoiceTax $invoiceTax)
    {
        $invoiceId = $invoiceTax->invoice_id;

        $invoiceTax->delete();

        return redirect()
            ->route('invoices.show', $invoiceId)
            ->with('success', 'Data pajak berhasil dihapus.');
    }
}