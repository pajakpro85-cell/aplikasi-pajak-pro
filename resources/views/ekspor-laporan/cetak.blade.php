<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan | TaxCalc Unifikasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff !important; }
            .print-card { box-shadow: none !important; border: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-10" x-data="cetakLaporanPdfPage()">

    {{-- Toolbar (nggak ikut kecetak) --}}
    <div class="no-print max-w-4xl mx-auto mb-5 flex items-center justify-between px-4">
        <button type="button" onclick="window.close()" class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            Tutup Tab
        </button>
        <button type="button" @click="cetak()"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak / Simpan sebagai PDF
        </button>
    </div>

    {{-- ==================== Voucher (yang beneran kecetak) ==================== --}}
    <div class="print-card max-w-4xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="bg-slate-950 text-white px-8 py-6">
            <p class="text-[11px] font-semibold tracking-wide text-slate-400">LEMBAR APPROVAL / VOUCHER</p>
            <h1 class="text-lg font-bold mt-0.5">DAFTAR TAGIHAN CETAK — REKAPITULASI MASA</h1>
            <p class="text-xs text-blue-400 mt-1.5">
                Masa <span x-text="masaLabelPendek"></span> <span x-text="tahun"></span> &bull; <span x-text="buyer.name"></span>
            </p>
        </div>

        <div class="px-8 py-6 text-sm">

            <table class="w-full text-xs border-collapse">
                <thead>
                    <tr class="text-left border-b-2 border-slate-800">
                        <th class="py-2 pr-2 font-bold">No. Invoice</th>
                        <th class="py-2 pr-2 font-bold">Klien / Vendor</th>
                        <th class="py-2 pr-2 font-bold">Jenis PPh</th>
                        <th class="py-2 pr-2 font-bold text-right">DPP</th>
                        <th class="py-2 pr-2 font-bold text-right">Potongan PPh</th>
                        <th class="py-2 pr-2 font-bold text-right">Bersih Ditransfer</th>
                        <th class="py-2 font-bold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="row in rows" :key="row.id">
                        <tr class="border-b border-slate-100">
                            <td class="py-2.5 pr-2 font-mono" x-text="row.noInvoice"></td>
                            <td class="py-2.5 pr-2" x-text="row.klien"></td>
                            <td class="py-2.5 pr-2" x-text="row.jenisPphDisplay"></td>
                            <td class="py-2.5 pr-2 text-right font-mono" x-text="formatIDR(row.dppRaw)"></td>
                            <td class="py-2.5 pr-2 text-right font-mono" x-text="formatIDR(row.potonganRaw)"></td>
                            <td class="py-2.5 pr-2 text-right font-mono font-semibold" x-text="formatIDR(row.bersihRaw)"></td>
                            <td class="py-2.5" x-text="row.status"></td>
                        </tr>
                    </template>

                    <tr x-show="rows.length === 0">
                        <td colspan="7" class="py-10 text-center text-slate-400">Tidak ada tagihan pada masa ini.</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-800 font-bold">
                        <td colspan="3" class="py-3 pr-2 text-right">TOTAL</td>
                        <td class="py-3 pr-2 text-right font-mono" x-text="formatIDR(totalDPP)"></td>
                        <td class="py-3 pr-2 text-right font-mono" x-text="formatIDR(totalPotongan)"></td>
                        <td class="py-3 pr-2 text-right font-mono" x-text="formatIDR(totalBersih)"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-10 grid grid-cols-2 gap-8 text-xs">
                <div>
                    <p class="text-slate-400 mb-14">Disiapkan oleh,</p>
                    <p class="border-t border-slate-400 pt-1 w-48">Finance &amp; Tax Staff</p>
                </div>
                <div>
                    <p class="text-slate-400 mb-14">Disetujui oleh,</p>
                    <p class="border-t border-slate-400 pt-1 w-48">Finance &amp; Tax Manager</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>