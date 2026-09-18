<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    /**
     * Menampilkan daftar jurnal.
     */
    public function index()
    {
        $journalEntries = JournalEntry::with('invoice.vendor')
            ->orderByDesc('tanggal_jurnal')
            ->get();

        return view('journal_entries.index', compact('journalEntries'));
    }

    /**
     * Menampilkan form tambah jurnal.
     */
    public function create()
    {
        $invoices = Invoice::with('vendor')
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view('journal_entries.create', compact('invoices'));
    }

    /**
     * Menyimpan jurnal baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id_invoice',
            'nomor_jurnal' => 'required|string|max:100',
            'tanggal_jurnal' => 'required|date',
            'akun' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        JournalEntry::create($validated);

        return redirect()
            ->route('journal-entries.index')
            ->with('success', 'Jurnal berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail jurnal.
     */
    public function show(JournalEntry $journalEntry)
    {
        $journalEntry->load('invoice.vendor');

        return view(
            'journal_entries.show',
            compact('journalEntry')
        );
    }

    /**
     * Menampilkan form edit jurnal.
     */
    public function edit(JournalEntry $journalEntry)
    {
        $invoices = Invoice::with('vendor')
            ->orderByDesc('tanggal_invoice')
            ->get();

        return view(
            'journal_entries.edit',
            compact('journalEntry', 'invoices')
        );
    }

    /**
     * Memperbarui data jurnal.
     */
    public function update(
        Request $request,
        JournalEntry $journalEntry
    ) {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id_invoice',
            'nomor_jurnal' => 'required|string|max:100',
            'tanggal_jurnal' => 'required|date',
            'akun' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'debit' => 'required|numeric|min:0',
            'credit' => 'required|numeric|min:0',
        ]);

        $journalEntry->update($validated);

        return redirect()
            ->route('journal-entries.index')
            ->with('success', 'Jurnal berhasil diperbarui.');
    }

    /**
     * Menghapus jurnal.
     */
    public function destroy(JournalEntry $journalEntry)
    {
        $journalEntry->delete();

        return redirect()
            ->route('journal-entries.index')
            ->with('success', 'Jurnal berhasil dihapus.');
    }
}