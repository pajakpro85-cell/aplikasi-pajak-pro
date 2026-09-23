<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="rekapitulasi" />

        <div class="flex-1 h-screen flex flex-col min-w-0">

            {{-- ---- TOP BAR (statis) ---- --}}
            <header class="shrink-0 bg-white border-b border-slate-200 px-8 py-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 font-medium px-3 py-1 text-xs">
                        PPh Unifikasi (PER-24/PJ/2021)
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">Pph 23</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">Pph 4(2)</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">Pph 22</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">Pph 15</span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="text-slate-500">Pph 26</span>
                </div>
            </header>

            {{-- ---- MAIN CONTENT (scroll vertikal saja) ---- --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Rekapitulasi Tagihan &amp; Pajak Invoice</h1>
                    <p class="text-sm text-slate-500 mt-1">Ringkasan konsolidasi nilai tagihan (DPP), potongan PPh Unifikasi &amp; pembayaran bersih &bull; Tahun <span class="font-semibold text-slate-700">2026</span></p>
                </div>

                {{-- ================= TAB NAVIGASI (reusable) ================= --}}
                <x-rekap-tabs active="klaster-pph" />

                @php
                    // Dummy data — nanti diagregasi dari data tagihan sesungguhnya per jenis PPh.
                    $klasterRows = [
                        ['jenis' => 'Pph Pasal 23', 'regulasi' => 'UU PPh Pasal 23 / PMK-141/2015', 'jumlah' => 1, 'dpp' => 'IDR 75.000.000', 'ppn' => 'PPN 11%: RP8.250.000', 'total' => 'IDR 83.250.000', 'potongan' => 'IDR 1.500.000', 'bersih' => 'IDR 81.750.000'],
                        ['jenis' => 'PPh Pasal 4 ayat (2)', 'regulasi' => 'UU PPh Pasal 4 ayat (2) Final', 'jumlah' => 1, 'dpp' => 'IDR 250.000.000', 'ppn' => 'PPN 11%: RP27.500.000', 'total' => 'IDR 277.500.000', 'potongan' => 'IDR 25.000.000', 'bersih' => 'IDR 252.500.000'],
                        ['jenis' => 'PPh Pasal 22', 'regulasi' => 'UU PPh Pasal 22 / PMK-34/2017', 'jumlah' => 1, 'dpp' => 'IDR 120.000.000', 'ppn' => 'PPN 11%: RP13.200.000', 'total' => 'IDR 133.200.000', 'potongan' => 'IDR 1.440.000', 'bersih' => 'IDR 131.760.000'],
                        ['jenis' => 'PPh Pasal 15', 'regulasi' => 'UU PPh Pasal 15 Norma Khusus', 'jumlah' => 1, 'dpp' => 'IDR 180.000.000', 'ppn' => null, 'total' => 'IDR 180.000.000', 'potongan' => 'IDR 20.000.000', 'bersih' => 'IDR 180.000.000'],
                        ['jenis' => 'PPh Pasal 26', 'regulasi' => 'UU PPh Pasal 26 / P3B Tax Treaty', 'jumlah' => 0, 'dpp' => 'IDR 0', 'ppn' => null, 'total' => 'IDR 0', 'potongan' => 'IDR 0', 'bersih' => 'IDR 0'],
                    ];
                @endphp

                {{-- ================= TABEL: RINCIAN PER JENIS PAJAK (scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900">Rincian per Kode Objek Pajak (KOP)</h2>
                        <span class="text-xs text-slate-400">Perhitungan Invoice Klien</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1200px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">JENIS PAJAK</th>
                                    <th class="px-6 py-3 whitespace-nowrap">DASAR REGULASI</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">JUMLAH TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">NILAI POKOK (DPP)</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">PPN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">POTONGAN PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TRANSFER BERSIH</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($klasterRows as $row)
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4 font-semibold text-slate-800 whitespace-nowrap">{{ $row['jenis'] }}</td>
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $row['regulasi'] }}</td>
                                        <td class="px-6 py-4 text-center font-mono text-slate-700">{{ $row['jumlah'] }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap">{{ $row['dpp'] }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-blue-600 whitespace-nowrap">{{ $row['ppn'] ?? '–' }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap">{{ $row['total'] }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-red-500 whitespace-nowrap">{{ $row['potongan'] }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-emerald-600 font-semibold whitespace-nowrap">{{ $row['bersih'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

</x-app-layout>