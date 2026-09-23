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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="rekapLawanTransaksiPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Rekapitulasi Tagihan &amp; Pajak Invoice</h1>
                    <p class="text-sm text-slate-500 mt-1">Ringkasan konsolidasi nilai tagihan (DPP), potongan PPh Unifikasi &amp; pembayaran bersih &bull; Tahun <span class="font-semibold text-slate-700" x-text="tahunSelected"></span></p>
                </div>

                {{-- ================= TAB NAVIGASI (reusable) ================= --}}
                <x-rekap-tabs active="lawan-transaksi" />

                {{-- ================= HEADER CARD: judul + dropdown Tahun Pajak ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6" @click.away="closeAll()">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <h2 class="font-bold text-slate-900">Rekapitulasi Akumulasi per Klien / Vendor (YTD)</h2>
                            <p class="text-sm text-slate-400 mt-0.5">Daftar Wajib Pajak yang telah dihitung potongan pajak invoice-nya</p>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span class="text-xs font-semibold tracking-wide text-slate-500">TAHUN PAJAK:</span>
                            <div class="relative">
                                <button type="button" @click="toggle('tahun')"
                                    class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                                    :class="open.tahun ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                                    <span class="text-slate-800" x-text="tahunSelected"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.tahun }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div x-show="open.tahun" x-cloak x-transition
                                    class="absolute right-0 z-20 mt-2 w-24 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                    <template x-for="y in tahunList" :key="y">
                                        <button type="button" @click="tahunSelected = y; open.tahun = false"
                                            class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                            :class="tahunSelected === y ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                            <span x-text="y"></span>
                                            <svg x-show="tahunSelected === y" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= TABEL: KLIEN/VENDOR (scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1300px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">KLIEN / VENDOR</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KATEGORI</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KLASTER PPH TERKAIT</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">JML TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL NILAI TAGIHAN (DPP)</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">PPN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL POTONGAN PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL BERSIH DITRANSFER</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(row, idx) in klienRows" :key="idx">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color:#BFDBFE">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#1042AE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="4" y="2" width="16" height="20" rx="1"/>
                                                        <path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-800 whitespace-nowrap" x-text="row.nama"></p>
                                                    <p class="text-xs text-slate-400 font-mono whitespace-nowrap" x-text="row.npwp"></p>
                                                    <p x-show="row.negara" class="text-xs font-medium text-blue-600 whitespace-nowrap" x-text="row.negara"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-md text-xs font-medium px-2.5 py-1 whitespace-nowrap border"
                                                :class="row.kategori === 'Badan' ? 'border-blue-300 text-blue-700' : 'border-purple-200 text-purple-600'"
                                                :style="row.kategori === 'Badan' ? 'background-color:#A5C1FF' : 'background-color:#F3E8FF'"
                                                x-text="row.kategori"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1 whitespace-nowrap" x-text="row.klaster"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-slate-700" x-text="row.jumlahTagihan"></td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap" x-text="row.nilaiDPP"></td>
                                        <td class="px-6 py-4 text-right font-mono text-blue-600 whitespace-nowrap" x-text="row.ppn ?? '\u2013'"></td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap" x-text="row.totalTagihan"></td>
                                        <td class="px-6 py-4 text-right font-mono text-red-500 whitespace-nowrap" x-text="row.potonganPPh"></td>
                                        <td class="px-6 py-4 text-right font-mono text-emerald-600 font-semibold whitespace-nowrap" x-text="row.bersihDitransfer"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

</x-app-layout>