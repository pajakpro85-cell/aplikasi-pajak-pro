<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar laporan pajak.
     */
    public function index()
    {
        $reports = Report::with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('reports.index', compact('reports'));
    }

    /**
     * Menampilkan form untuk membuat laporan.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Membuat dan menyimpan data laporan pajak.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'masa_pajak' => 'required|string|max:20',
            'tahun_pajak' => 'required|integer|min:2000|max:2100',
            'jenis_laporan' => 'required|string|max:100',
            'nama_file' => 'required|string|max:255',
            'lokasi_file' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();

        Report::create($validated);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan pajak berhasil disimpan.');
    }

    /**
     * Menampilkan detail laporan.
     */
    public function show(Report $report)
    {
        $report->load('user');

        return view('reports.show', compact('report'));
    }

    /**
     * Menampilkan form edit laporan.
     */
    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    /**
     * Memperbarui data laporan.
     */
    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'masa_pajak' => 'required|string|max:20',
            'tahun_pajak' => 'required|integer|min:2000|max:2100',
            'jenis_laporan' => 'required|string|max:100',
            'nama_file' => 'required|string|max:255',
            'lokasi_file' => 'required|string|max:255',
        ]);

        $report->update($validated);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan pajak berhasil diperbarui.');
    }

    /**
     * Menghapus laporan.
     */
    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan pajak berhasil dihapus.');
    }

    /**
     * Menampilkan rekap pajak berdasarkan periode.
     */
    public function recap(Request $request)
    {
        $query = Invoice::with([
            'vendor',
            'invoiceTaxes.taxObject.taxCluster',
            'payments',
        ]);

        // Filter berdasarkan masa pajak
        if ($request->filled('masa_pajak')) {
            $query->where('masa_pajak', $request->masa_pajak);
        }

        // Filter berdasarkan tahun pajak
        if ($request->filled('tahun_pajak')) {
            $query->where('tahun_pajak', $request->tahun_pajak);
        }

        $invoices = $query
            ->orderBy('tanggal_invoice')
            ->get();

        return view('reports.recap', compact(
            'invoices'
        ));
    }
}