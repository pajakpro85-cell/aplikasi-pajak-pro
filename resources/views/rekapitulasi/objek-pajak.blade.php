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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="rekapObjekPajakPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Rekapitulasi Tagihan &amp; Pajak Invoice</h1>
                    <p class="text-sm text-slate-500 mt-1">
                        Ringkasan konsolidasi nilai tagihan (DPP), potongan PPh Unifikasi &amp; pembayaran bersih &bull; Tahun <span class="font-semibold text-slate-700" x-text="tahunSelected"></span>
                    </p>
                </div>

                {{-- ================= TAB NAVIGASI (reusable, 3 halaman terpisah) ================= --}}
                <x-rekap-tabs active="objek-pajak" />

                {{-- ================= FILTER MASA/TAHUN + TOTAL POTONGAN ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6" @click.away="closeAll()">
                    <div class="flex items-center justify-between gap-4 flex-wrap">

                        <div class="flex items-center gap-3">
                            {{-- Dropdown Masa --}}
                            <div class="relative">
                                <button type="button" @click="toggle('masa')"
                                    class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                                    :class="open.masa ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                    <span class="text-slate-800" x-text="masaSelected"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.masa }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div x-show="open.masa" x-cloak x-transition
                                    class="absolute left-0 z-20 mt-2 w-56 max-h-72 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                    <template x-for="m in masaBulanList" :key="m">
                                        <button type="button" @click="masaSelected = m; open.masa = false"
                                            class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                            :class="masaSelected === m ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                            <span x-text="m"></span>
                                            <svg x-show="masaSelected === m" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            {{-- Dropdown Tahun --}}
                            <div class="relative">
                                <button type="button" @click="toggle('tahun')"
                                    class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                                    :class="open.tahun ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                                    <span class="text-slate-800" x-text="tahunSelected"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.tahun }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                </button>
                                <div x-show="open.tahun" x-cloak x-transition
                                    class="absolute left-0 z-20 mt-2 w-24 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
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

                        <div class="text-right">
                            <p class="text-xs font-semibold tracking-wide text-slate-400">TOTAL POTONGAN PPH (<span x-text="masaLabelPendek.toUpperCase()"></span>)</p>
                            <p class="text-2xl font-bold text-red-500 font-mono mt-1" x-text="totalPotonganPPh"></p>
                        </div>
                    </div>
                </div>

                {{-- ================= 3 SUMMARY CARDS ================= --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
                    <div class="rounded-2xl p-5 flex items-center gap-4" style="background-color:#DBEAFE">
                        <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 4-8 4-8-4 8-4Z"/><path d="m4 11 8 4 8-4"/><path d="m4 15 8 4 8-4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-blue-900/60">TOTAL TAGIHAN POKOK (DPP)</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-0.5" x-text="totalTagihanPokok"></p>
                        </div>
                    </div>

                    <div class="rounded-2xl p-5 flex items-center gap-4" style="background-color:#D1FAE5">
                        <div class="w-11 h-11 rounded-xl bg-emerald-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-emerald-900/60">TOTAL TRANSFER BERSIH</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-0.5" x-text="totalTransferBersih"></p>
                        </div>
                    </div>

                    <div class="rounded-2xl p-5 flex items-center gap-4" style="background-color:#EDE4FF">
                        <div class="w-11 h-11 rounded-xl bg-purple-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-purple-900/60">JUMLAH KLIEN / VENDOR</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-0.5"><span x-text="jumlahKlienVendor"></span> LAWAN TRANSAKSI</p>
                        </div>
                    </div>
                </div>

                {{-- ================= TABEL: RINCIAN PER KOP (scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900">Rincian per Kode Objek Pajak (KOP)</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1200px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">KODE OBJEK</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KLASTER PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">JUMLAH TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">NILAI TAGIHAN (DPP)</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">PPN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TOTAL TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">POTONGAN PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TRANSFER BERSIH</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in kopRows" :key="row.kode">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-slate-800 font-mono whitespace-nowrap" x-text="row.kode"></p>
                                            <p class="text-xs text-slate-400 mt-0.5 whitespace-nowrap" x-text="row.desk"></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1 whitespace-nowrap" x-text="row.klaster"></span>
                                        </td>
                                        <td class="px-6 py-4 text-center font-mono text-slate-700" x-text="row.jumlahTagihan"></td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap" x-text="row.nilaiDPP"></td>
                                        <td class="px-6 py-4 text-right font-mono text-blue-600 whitespace-nowrap" x-text="row.ppn ?? '\u2013'"></td>
                                        <td class="px-6 py-4 text-right font-mono text-slate-700 whitespace-nowrap" x-text="row.totalTagihan"></td>
                                        <td class="px-6 py-4 text-right font-mono text-red-500 whitespace-nowrap" x-text="row.potonganPPh"></td>
                                        <td class="px-6 py-4 text-right font-mono text-emerald-600 font-semibold whitespace-nowrap" x-text="row.transferBersih"></td>
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