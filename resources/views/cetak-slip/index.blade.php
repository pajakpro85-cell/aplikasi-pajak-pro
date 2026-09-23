<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Slip - {{ $id }} | TaxCalc Unifikasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; }
            .print-card { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10" x-data="cetakSlipPage('{{ $id }}')">

    {{-- Toolbar (nggak ikut kecetak) --}}
    <div class="no-print max-w-3xl mx-auto mb-5 flex items-center justify-between px-4">
        <a href="{{ url('/daftar-tagihan') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Daftar Tagihan
        </a>
        <button type="button" @click="cetak()"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak / Simpan sebagai PDF
        </button>
    </div>

    {{-- ==================== Slip (yang beneran kecetak) ==================== --}}
    <template x-if="row">
        <div class="print-card max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="bg-slate-950 text-white px-8 py-6">
                <p class="text-[11px] font-semibold tracking-wide text-slate-400">LEMBAR PERHITUNGAN PAJAK INVOICE</p>
                <h1 class="text-lg font-bold mt-0.5">SLIP POTONGAN PEMBAYARAN TAGIHAN KLIEN</h1>
                <p class="text-xs text-blue-400 mt-1.5 font-mono">Invoice: <span x-text="row.noInvoice"></span></p>
            </div>

            <div class="px-8 py-6 text-sm">

                {{-- Masa/Tahun + Metode --}}
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <p class="text-xs text-slate-400">Masa / Tahun Pembukuan</p>
                        <p class="text-lg font-bold text-slate-900" x-text="row.masaBulan.split(' (')[0] + ' ' + row.tahun"></p>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">Tgl Invoice: <span x-text="row.tanggalISO"></span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400 mb-1">Metode Potongan</p>
                        <span class="inline-flex items-center rounded-md border border-blue-500 text-blue-700 text-xs font-semibold px-2.5 py-1" x-text="row.metodePotongan"></span>
                    </div>
                </div>

                <div class="my-5 border-t border-slate-100"></div>

                {{-- A & B --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs font-bold text-blue-600 mb-1.5">A. PERUSAHAAN PEMOTONG (BUYER)</p>
                        <p class="font-semibold text-slate-800" x-text="buyer.name"></p>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">NPWP: <span x-text="buyer.npwp"></span></p>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed" x-text="buyer.alamat"></p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-emerald-600 mb-1.5">B. KLIEN / VENDOR (PENERIMA PENGHASILAN)</p>
                        <p class="font-semibold text-slate-800" x-text="row.klien"></p>
                        <p class="text-xs text-slate-500 font-mono mt-0.5">NPWP/TIN: <span x-text="row.npwp"></span></p>
                        <p class="text-xs text-slate-400 mt-1 leading-relaxed" x-text="row.klienAlamat"></p>
                    </div>
                </div>

                <div class="my-5 border-t border-slate-100"></div>

                {{-- C: Rincian --}}
                <p class="text-xs font-bold text-slate-700 mb-2.5">C. RINCIAN PERHITUNGAN TAGIHAN &amp; PAJAK</p>
                <div class="rounded-xl bg-slate-50 p-4">
                    <div class="flex items-start justify-between gap-3">
                        <p class="font-semibold text-slate-800 text-[13px]">
                            [<span x-text="row.kopCode"></span>] <span x-text="row.jenisPphDisplay"></span>
                        </p>
                        <p class="text-xs text-slate-400 font-mono whitespace-nowrap">Ref Inv: <span x-text="row.noInvoice"></span></p>
                    </div>
                    <p class="text-xs text-slate-400 mt-1" x-text="row.kopDesc"></p>

                    <div class="my-3 border-t border-slate-200"></div>

                    <div class="space-y-1.5 text-[13px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Nilai Pokok Tagihan (DPP):</span>
                            <span class="font-mono font-semibold text-slate-800" x-text="row.dppDisplay"></span>
                        </div>
                        <div class="flex items-center justify-between" x-show="row.ppnRaw > 0">
                            <span class="text-emerald-600">PPN Masukan (11%):</span>
                            <span class="font-mono font-semibold text-emerald-600" x-text="'+ ' + formatIDR(row.ppnRaw)"></span>
                        </div>
                    </div>

                    <div class="my-3 border-t border-slate-200"></div>

                    <div class="space-y-1.5 text-[13px]">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Total Tagihan Bruto (DPP + PPN):</span>
                            <span class="font-mono font-semibold text-slate-800" x-text="formatIDR(totalBruto)"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-red-500">Potongan <span x-text="row.jenisPphDisplay"></span> (<span x-text="row.tarifDisplay"></span>):</span>
                            <span class="font-mono font-semibold text-red-500" x-text="'- ' + row.potonganDisplay"></span>
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-bold tracking-wide text-blue-700">JUMLAH BERSIH DITRANSFER KE KLIEN</p>
                            <p class="text-xs text-blue-500">Net Payment Transfer</p>
                        </div>
                        <p class="text-lg font-bold text-blue-700 font-mono whitespace-nowrap" x-text="row.bersihDisplay"></p>
                    </div>
                </div>

                <div class="my-5 border-t border-slate-100"></div>

                {{-- D: Jurnal --}}
                <p class="text-xs font-bold text-slate-700 mb-2.5">D. CATATAN JURNAL KEUANGAN</p>
                <div class="rounded-lg bg-slate-950 p-4 font-mono text-xs space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="text-emerald-400">(Dr) Beban Jasa / Operasional</span>
                        <span class="text-white" x-text="row.dppDisplay"></span>
                    </div>
                    <div class="flex items-center justify-between" x-show="row.ppnRaw > 0">
                        <span class="text-emerald-400">(Dr) PPN Masukan</span>
                        <span class="text-white" x-text="formatIDR(row.ppnRaw)"></span>
                    </div>
                    <div class="flex items-center justify-between pl-3">
                        <span class="text-amber-400">(Cr) Hutang <span x-text="row.jenisPphDisplay"></span></span>
                        <span class="text-amber-300" x-text="row.potonganDisplay"></span>
                    </div>
                    <div class="flex items-center justify-between pl-3">
                        <span class="text-amber-400">(Cr) Kas / Bank Transfer</span>
                        <span class="text-amber-300" x-text="row.bersihDisplay"></span>
                    </div>
                </div>

                <div class="my-5 border-t border-slate-100"></div>

                {{-- Footer --}}
                <div class="flex items-end justify-between gap-4 flex-wrap pb-1">
                    <div>
                        <p class="text-xs text-slate-500">Penandatangan: <span class="font-semibold text-slate-700" x-text="penandatangan.nama"></span></p>
                        <p class="text-xs text-slate-400 mt-0.5">Jabatan: <span x-text="penandatangan.jabatan"></span></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-500">
                            Status: <span class="font-semibold" :class="row.status === 'Sudah Dibayar & Dipotong' ? 'text-emerald-600' : 'text-blue-600'" x-text="row.status"></span>
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">Tgl Catat: <span x-text="row.tglCatat"></span></p>
                    </div>
                </div>

            </div>
        </div>
    </template>

    {{-- Data tidak ditemukan --}}
    <template x-if="!row">
        <div class="max-w-3xl mx-auto bg-white rounded-2xl border border-slate-200 py-20 text-center text-slate-400">
            Data tagihan tidak ditemukan.
        </div>
    </template>

</body>
</html>