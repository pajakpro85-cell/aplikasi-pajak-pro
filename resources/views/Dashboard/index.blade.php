<x-app-layout>

    {{-- ================= ROOT: fixed viewport, no page-level scroll ================= --}}
    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        {{-- ================= SIDEBAR (reusable component, statis di-scroll) ================= --}}
        <x-sidebar active="dashboard" />

        {{-- ================= RIGHT SIDE: topbar static + scrollable content ================= --}}
        <div class="flex-1 h-screen flex flex-col min-w-0">

            {{-- ---- TOP BAR (static, non-scrolling) ---- --}}
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

            {{-- ---- MAIN CONTENT (only this scrolls vertically) ---- --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6">

                {{-- Page heading + filters --}}
                <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Dashboard Pajak Invoice Klien</h1>
                        <p class="text-sm text-slate-500 mt-1">Ringkasan perhitungan tagihan, potongan PPh Unifikasi &amp; transfer bersih masa Agustus 2026</p>
                    </div>

                    <div class="flex items-center gap-3"
                        x-data="{
                            monthOpen: false,
                            yearOpen: false,
                            months: [
                                'Januari (Masa 1)', 'Februari (Masa 2)', 'Maret (Masa 3)', 'April (Masa 4)',
                                'Mei (Masa 5)', 'Juni (Masa 6)', 'Juli (Masa 7)', 'Agustus (Masa 8)',
                                'September (Masa 9)', 'Oktober (Masa 10)', 'November (Masa 11)', 'Desember (Masa 12)'
                            ],
                            years: [2024, 2025, 2026, 2027],
                            selectedMonth: 'Agustus (Masa 8)',
                            selectedYear: 2026
                        }">

                        {{-- Dropdown Bulan --}}
                        <div class="relative" @click.away="monthOpen = false">
                            <button type="button" @click="monthOpen = !monthOpen; yearOpen = false"
                                class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <span x-text="selectedMonth"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': monthOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>

                            <div x-show="monthOpen" x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 z-20 mt-2 w-52 max-h-72 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5 origin-top-right">
                                <template x-for="m in months" :key="m">
                                    <button type="button" @click="selectedMonth = m; monthOpen = false"
                                        class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm hover:bg-slate-50 transition"
                                        :class="selectedMonth === m ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="m"></span>
                                        <svg x-show="selectedMonth === m" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dropdown Tahun --}}
                        <div class="relative" @click.away="yearOpen = false">
                            <button type="button" @click="yearOpen = !yearOpen; monthOpen = false"
                                class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:border-slate-300 transition">
                                <span x-text="selectedYear"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': yearOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>

                            <div x-show="yearOpen" x-cloak
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 z-20 mt-2 w-28 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5 origin-top-right">
                                <template x-for="y in years" :key="y">
                                    <button type="button" @click="selectedYear = y; yearOpen = false"
                                        class="w-full flex items-center justify-between gap-2 px-4 py-2 text-sm hover:bg-slate-50 transition"
                                        :class="selectedYear === y ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="y"></span>
                                        <svg x-show="selectedYear === y" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <a href="{{ url('/hitung-tagihan') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            Hitung Tagihan Baru
                        </a>
                    </div>
                </div>

                {{-- KPI cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 4-8 4-8-4 8-4Z"/><path d="m4 11 8 4 8-4"/><path d="m4 15 8 4 8-4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-500">TOTAL NILAI TAGIHAN (DPP)</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-1">IDR 625 jt</p>
                            <p class="text-xs text-slate-400 mt-0.5">IDR 625.000.000</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-500">TOTAL NILAI TAGIHAN (DPP)</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-1">IDR 47.950.000</p>
                            <p class="text-xs text-slate-400 mt-0.5">4 Tagihan Terhitung</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-500">TOTAL NILAI TAGIHAN (DPP)</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-1">IDR 625 jt</p>
                            <p class="text-xs text-slate-400 mt-0.5">IDR 646.010.000</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 17 6-6 4 4 8-8"/><path d="M17 7h4v4"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-slate-500">TOTAL NILAI TAGIHAN (DPP)</p>
                            <p class="text-lg font-bold text-slate-900 font-mono mt-1">IDR 625 jt</p>
                            <p class="text-xs text-slate-400 mt-0.5">Tahun 2026 &bull; 4 Klien Aktif</p>
                        </div>
                    </div>
                </div>

                {{-- Charts row --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

                    {{-- Bar chart --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <h2 class="font-bold text-slate-900">Potongan PPh per Klaster Unifikasi</h2>
                                <p class="text-sm text-slate-400">Masa Agustus 2026</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 text-xs font-medium px-3 py-1 shrink-0">Tagihan Invoice</span>
                        </div>
                        <div class="relative mt-4" style="height: 320px;">
                            <canvas id="pphBarChart"></canvas>
                        </div>
                    </div>

                    {{-- Donut chart --}}
                    <div class="bg-white rounded-2xl border border-slate-200 p-6">
                        <div class="flex items-start justify-between mb-1">
                            <div>
                                <h2 class="font-bold text-slate-900">Distribusi Kategori Klien / Vendor</h2>
                                <p class="text-sm text-slate-400">Badan vs Orang Pribadi vs WPLN</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium px-3 py-1 shrink-0">Lawan Transaksi</span>
                        </div>
                        <div class="relative mt-4 flex items-center justify-center" style="height: 320px;">
                            <div style="width: 260px; height: 260px;">
                                <canvas id="klienDonutChart"></canvas>
                            </div>
                        </div>
                        <div class="flex items-center justify-center gap-6 mt-2">
                            <span class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-sm bg-blue-600 inline-block"></span> Badan (PT/CV)
                            </span>
                            <span class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <span class="w-2.5 h-2.5 rounded-sm bg-emerald-800 inline-block"></span> WPLN (Asing)
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Table card --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900">Daftar Tagihan Invoice Terbaru (Agustus 2026)</h2>
                        <a href="{{ url('/daftar-tagihan') }}" class="text-sm text-blue-600 font-medium hover:underline">Lihat Semua Tagihan</a>
                    </div>

                    {{-- Only this element scrolls horizontally --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1400px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">NO. INVOICE &amp; TANGGAL</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KLIEN / LAWAN TRANSAKSI</th>
                                    <th class="px-6 py-3 whitespace-nowrap">JENIS PPH &amp; OBJEK</th>
                                    <th class="px-6 py-3 whitespace-nowrap">NILAI POKOK (DPP)</th>
                                    <th class="px-6 py-3 whitespace-nowrap">PPN</th>
                                    <th class="px-6 py-3 whitespace-nowrap">TOTAL TAGIHAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap">TARIF</th>
                                    <th class="px-6 py-3 whitespace-nowrap">POTONGAN PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap">BERSIH DITRANSFER</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4"><a href="#" class="text-blue-600 font-mono text-[13px] font-medium">INV/GPS/2026/08/044</a></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">PT Graha Pratama Solusindo</p>
                                        <p class="text-xs text-slate-400 font-mono">02.456.789.1-013.000</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1">PPh Pasal 23</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">24-104-01</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 75.000.000</td>
                                    <td class="px-6 py-4 font-mono text-emerald-600 whitespace-nowrap">PPN 11%: RP8.250.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 83.250.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700">2%</td>
                                    <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap">IDR 1.500.000</td>
                                    <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap">IDR 81.750.000</td>
                                </tr>
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4"><a href="#" class="text-blue-600 font-mono text-[13px] font-medium">INV-SEWA-2026-081</a></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">PT Cipta Sarana Bangun Indonesia</p>
                                        <p class="text-xs text-slate-400 font-mono">03.112.233.4-021.000</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1">PPh Pasal 4 ayat (2)</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">28-402-01</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 250.000.000</td>
                                    <td class="px-6 py-4 font-mono text-emerald-600 whitespace-nowrap">PPN 11%: RP27.500.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 277.500.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700">10%</td>
                                    <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap">IDR 25.000.000</td>
                                    <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap">IDR 252.500.000</td>
                                </tr>
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4"><a href="#" class="text-blue-600 font-mono text-[13px] font-medium">INV/SBN/26/08/901</a></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">PT Samudera Bahtera Nusantara</p>
                                        <p class="text-xs text-slate-400 font-mono">01.998.877.6-044.000</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1">PPh Pasal 22</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">22-101-01</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 120.000.000</td>
                                    <td class="px-6 py-4 font-mono text-emerald-600 whitespace-nowrap">PPN 11%: RP13.200.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 133.200.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700">1.2%</td>
                                    <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap">IDR 1.440.000</td>
                                    <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap">IDR 131.760.000</td>
                                </tr>
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4"><a href="#" class="text-blue-600 font-mono text-[13px] font-medium">INV-CS-SG-2026-880</a></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">CloudScale Technologies Pte. Ltd.</p>
                                        <p class="text-xs text-slate-400 font-mono">TAXID-SG-201844919</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1">PPh Pasal 15</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">25-100-02</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 180.000.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-400 whitespace-nowrap">-</td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 180.000.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700">10%</td>
                                    <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap">IDR 20.000.000</td>
                                    <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap">IDR 180.000.000</td>
                                </tr>
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4"><a href="#" class="text-blue-600 font-mono text-[13px] font-medium">INV-MLS/VII/26/102</a></td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800">CV Mitra Logistik Sejahtera</p>
                                        <p class="text-xs text-slate-400 font-mono">CV Mitra Logistik Sejahtera</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1">PPh Pasal 23</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">24-104-05</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 45.000.000</td>
                                    <td class="px-6 py-4 font-mono text-emerald-600 whitespace-nowrap">PPN 11%: RP4.950.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap">IDR 49.950.000</td>
                                    <td class="px-6 py-4 font-mono text-slate-700">2%</td>
                                    <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap">IDR 900.000</td>
                                    <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap">IDR 49.050.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    {{--
        Chart.js diinisialisasi lewat resources/js/dashboard-charts.js
        (di-bundle oleh Vite via resources/js/app.js) — bukan CDN lagi,
        supaya nggak kena masalah CSP/network/urutan-load.
    --}}

</x-app-layout>