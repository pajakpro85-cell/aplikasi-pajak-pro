<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        {{-- ================= SIDEBAR ================= --}}
        <x-sidebar active="daftar-tagihan" />

        {{-- ================= RIGHT SIDE ================= --}}
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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6"
                  x-data="daftarTagihanPage()" @keydown.escape.window="cancelDelete()">

                {{-- Page heading --}}
                <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Daftar Perhitungan Pajak Invoice</h1>
                        <p class="text-sm text-slate-500 mt-1">Arsip tagihan klien &amp; perhitungan potongan PPh 23, 4(2), 22, 15, dan 26</p>
                    </div>

                    <a href="{{ url('/hitung-tagihan') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                        Hitung Pajak Tagihan Baru
                    </a>
                </div>

                {{-- ================= SEARCH + FILTER BAR ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-4 mb-6" @click.away="closeAll()">
                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Search --}}
                        <div class="relative flex-1 min-w-[240px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" x-model="search" placeholder="Cari No. Invoice, Nama Klien / Vendor, NPWP, atau kata kunci lain..."
                                class="w-full rounded-lg border border-slate-200 pl-10 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        {{-- Dropdown: Jenis PPh --}}
                        <div class="relative">
                            <button type="button" @click="toggle('jenisPph')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.jenisPph || filterJenisPph !== 'Semua Jenis PPh' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterJenisPph"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.jenisPph }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.jenisPph" x-cloak x-transition
                                class="absolute left-0 z-20 mt-2 w-56 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="opt in jenisPphList" :key="opt">
                                    <button type="button" @click="filterJenisPph = opt; open.jenisPph = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="filterJenisPph === opt ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="opt"></span>
                                        <svg x-show="filterJenisPph === opt" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dropdown: Kategori Klien --}}
                        <div class="relative">
                            <button type="button" @click="toggle('kategoriKlien')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.kategoriKlien || filterKategoriKlien !== 'Semua Kategori Klien' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterKategoriKlien"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.kategoriKlien }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.kategoriKlien" x-cloak x-transition
                                class="absolute left-0 z-20 mt-2 w-56 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="opt in kategoriKlienList" :key="opt">
                                    <button type="button" @click="filterKategoriKlien = opt; open.kategoriKlien = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="filterKategoriKlien === opt ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="opt"></span>
                                        <svg x-show="filterKategoriKlien === opt" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dropdown: Tahun --}}
                        <div class="relative">
                            <button type="button" @click="toggle('tahun')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.tahun || filterTahun !== 'Semua Tahun' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterTahun"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.tahun }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.tahun" x-cloak x-transition
                                class="absolute left-0 z-20 mt-2 w-32 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="opt in tahunList" :key="opt">
                                    <button type="button" @click="filterTahun = opt; open.tahun = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="filterTahun === opt ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="opt"></span>
                                        <svg x-show="filterTahun === opt" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dropdown: Masa --}}
                        <div class="relative">
                            <button type="button" @click="toggle('masa')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.masa || filterMasa !== 'Semua Masa' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterMasa"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.masa }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.masa" x-cloak x-transition
                                class="absolute left-0 z-20 mt-2 w-56 max-h-72 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="opt in masaList" :key="opt">
                                    <button type="button" @click="filterMasa = opt; open.masa = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="filterMasa === opt ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="opt"></span>
                                        <svg x-show="filterMasa === opt" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- Dropdown: Status --}}
                        <div class="relative">
                            <button type="button" @click="toggle('status')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.status || filterStatus !== 'Semua Status' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterStatus"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.status }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.status" x-cloak x-transition
                                class="absolute right-0 z-20 mt-2 w-52 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="opt in statusList" :key="opt">
                                    <button type="button" @click="filterStatus = opt; open.status = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="filterStatus === opt ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="opt"></span>
                                        <svg x-show="filterStatus === opt" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ================= TABLE (scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1500px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">NO. INVOICE &amp; TANGGAL</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KLIEN / LAWAN TRANSAKSI</th>
                                    <th class="px-6 py-3 whitespace-nowrap">JENIS PPH &amp; OBJEK</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">NILAI POKOK (DPP)</th>
                                    <th class="px-6 py-3 whitespace-nowrap">TARIF</th>
                                    <th class="px-6 py-3 whitespace-nowrap">POTONGAN PPH</th>
                                    <th class="px-6 py-3 whitespace-nowrap">BERSIH DITRANSFER</th>
                                    <th class="px-6 py-3 whitespace-nowrap">STATUS</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in filteredList" :key="row.id">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <a href="#" class="text-blue-600 font-mono text-[13px] font-medium" x-text="row.noInvoice"></a>
                                            <p class="text-xs text-slate-400 mt-0.5" x-text="row.tanggalDisplay + ' • ' + row.masaDisplay"></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                                    :style="row.kategoriKlien === 'WPLN (Luar Negeri)' ? 'background-color:#DAC4FF' : 'background-color:#BFDBFE'">
                                                    {{-- Icon Gedung (Badan/OP) --}}
                                                    <svg x-show="row.kategoriKlien !== 'WPLN (Luar Negeri)'" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#1042AE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="4" y="2" width="16" height="20" rx="1"/>
                                                        <path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/>
                                                    </svg>
                                                    {{-- Icon Globe/Language (WPLN) --}}
                                                    <svg x-show="row.kategoriKlien === 'WPLN (Luar Negeri)'" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#8C2DE2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="9"/>
                                                        <path d="M3 12h18"/>
                                                        <path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18Z"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-slate-800 whitespace-nowrap" x-text="row.klien"></p>
                                                    <p class="text-xs text-slate-400 font-mono whitespace-nowrap" x-text="row.npwp"></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2 py-1 whitespace-nowrap" x-text="row.jenisPphDisplay"></span>
                                                <span class="text-xs text-slate-400 font-mono" x-text="row.kopCode"></span>
                                            </div>
                                            <p class="text-xs text-slate-400 mt-1 whitespace-nowrap" x-text="row.kopDesc"></p>
                                            <p x-show="row.treaty" x-cloak class="text-xs font-medium mt-1 inline-flex items-center gap-1" style="color:#D97706">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 2 4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4Z"/>
                                                    <path d="m9 12 2 2 4-4"/>
                                                </svg>
                                                P3B / Tax Treaty (Form DGT)
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <p class="font-mono text-slate-700 whitespace-nowrap" x-text="row.dppDisplay"></p>
                                            <p x-show="row.ppnDisplay" class="font-mono text-emerald-600 text-xs whitespace-nowrap mt-0.5" x-text="row.ppnDisplay"></p>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-slate-700 whitespace-nowrap" x-text="row.tarifDisplay"></td>
                                        <td class="px-6 py-4 font-mono text-red-500 whitespace-nowrap" x-text="row.potonganDisplay"></td>
                                        <td class="px-6 py-4 font-mono text-blue-700 font-semibold whitespace-nowrap" x-text="row.bersihDisplay"></td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full text-xs font-medium px-3 py-1 whitespace-nowrap"
                                                :class="statusPillStyle(row.status).class"
                                                :style="statusPillStyle(row.status).style"
                                                x-text="row.status"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-3 text-slate-400">
                                                <button type="button" title="Lihat Detail" @click="showDetail(row.id)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                                <button type="button" title="Edit" @click="editRow(row)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                                </button>
                                                <button type="button" title="Duplikat" @click="copyRow(row)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                                </button>
                                                <button type="button" title="Hapus" @click="askDelete(row.id)" class="hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                {{-- Empty state kalau hasil search/filter kosong --}}
                                <tr x-show="filteredList.length === 0">
                                    <td colspan="9" class="px-6 py-16 text-center text-sm text-slate-400">
                                        Tidak ada tagihan yang cocok dengan pencarian/filter kamu.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= MODAL: DETAIL SLIP POTONGAN TAGIHAN ================= --}}
                <div x-show="detailRow !== null" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8"
                    style="background-color: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px);"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">

                    <div @click.away="closeDetail()" x-show="detailRow !== null"
                        class="bg-white rounded-2xl w-full max-w-xl max-h-[85vh] flex flex-col overflow-hidden"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <template x-if="detailRow">
                            <div class="flex flex-col min-h-0">

                                {{-- Header (dark, statis) --}}
                                <div class="shrink-0 bg-slate-950 text-white px-6 py-5 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-[11px] font-semibold tracking-wide text-slate-400">LEMBAR PERHITUNGAN PAJAK INVOICE</p>
                                        <h2 class="text-base font-bold mt-0.5">SLIP POTONGAN PEMBAYARAN TAGIHAN KLIEN</h2>
                                        <p class="text-xs text-slate-400 mt-1 font-mono">Invoice: <span x-text="detailRow.noInvoice"></span></p>
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a :href="cetakSlipUrl(detailRow)" target="_blank"
                                            class="inline-flex items-center gap-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-white text-xs font-semibold px-3.5 py-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                            Cetak Slip
                                        </a>
                                        <button type="button" @click="closeDetail()" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Body (scroll vertikal) --}}
                                <div class="overflow-y-auto px-6 py-5 text-sm">

                                    {{-- Masa/Tahun + Metode Potongan --}}
                                    <div class="flex items-start justify-between gap-4 flex-wrap">
                                        <div>
                                            <p class="text-xs text-slate-400">Masa / Tahun Pembukuan</p>
                                            <p class="text-lg font-bold text-slate-900" x-text="detailRow.masaBulan.split(' (')[0] + ' ' + detailRow.tahun"></p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5">Tgl Invoice: <span x-text="detailRow.tanggalISO"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-400 mb-1">Metode Potongan</p>
                                            <span class="inline-flex items-center rounded-md border border-blue-500 text-blue-700 text-xs font-semibold px-2.5 py-1" x-text="detailRow.metodePotongan"></span>
                                        </div>
                                    </div>

                                    <div class="my-4 border-t border-slate-100"></div>

                                    {{-- A & B: Buyer / Klien --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <p class="text-xs font-bold text-blue-600 mb-1.5">A. PERUSAHAAN PEMOTONG (BUYER)</p>
                                            <p class="font-semibold text-slate-800" x-text="buyer.name"></p>
                                            <p class="text-xs text-slate-500 font-mono mt-0.5">NPWP: <span x-text="buyer.npwp"></span></p>
                                            <p class="text-xs text-slate-400 mt-1 leading-relaxed" x-text="buyer.alamat"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-emerald-600 mb-1.5">B. KLIEN / VENDOR (PENERIMA PENGHASILAN)</p>
                                            <p class="font-semibold text-slate-800" x-text="detailRow.klien"></p>
                                            <p class="text-xs text-slate-500 font-mono mt-0.5">NPWP/TIN: <span x-text="detailRow.npwp"></span></p>
                                            <p class="text-xs text-slate-400 mt-1 leading-relaxed" x-text="detailRow.klienAlamat"></p>
                                        </div>
                                    </div>

                                    <div class="my-4 border-t border-slate-100"></div>

                                    {{-- C: Rincian Perhitungan --}}
                                    <p class="text-xs font-bold text-slate-700 mb-2.5">C. RINCIAN PERHITUNGAN TAGIHAN &amp; PAJAK</p>
                                    <div class="rounded-xl bg-slate-50 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="font-semibold text-slate-800 text-[13px]">
                                                [<span x-text="detailRow.kopCode"></span>] <span x-text="detailRow.jenisPphDisplay"></span>
                                            </p>
                                            <p class="text-xs text-slate-400 font-mono whitespace-nowrap">Ref Inv: <span x-text="detailRow.noInvoice"></span></p>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1" x-text="detailRow.kopDesc"></p>

                                        <div class="my-3 border-t border-slate-200"></div>

                                        <div class="space-y-1.5 text-[13px]">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Nilai Pokok Tagihan (DPP):</span>
                                                <span class="font-mono font-semibold text-slate-800" x-text="detailRow.dppDisplay"></span>
                                            </div>
                                            <div class="flex items-center justify-between" x-show="detailRow.ppnRaw > 0">
                                                <span class="text-emerald-600">PPN Masukan (11%):</span>
                                                <span class="font-mono font-semibold text-emerald-600" x-text="'+ ' + formatIDR(detailRow.ppnRaw)"></span>
                                            </div>
                                        </div>

                                        <div class="my-3 border-t border-slate-200"></div>

                                        <div class="space-y-1.5 text-[13px]">
                                            <div class="flex items-center justify-between">
                                                <span class="text-slate-500">Total Tagihan Bruto (DPP + PPN):</span>
                                                <span class="font-mono font-semibold text-slate-800" x-text="formatIDR(detailTotalBruto)"></span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="text-red-500">Potongan <span x-text="detailRow.jenisPphDisplay"></span> (<span x-text="detailRow.tarifDisplay"></span>):</span>
                                                <span class="font-mono font-semibold text-red-500" x-text="'- ' + detailRow.potonganDisplay"></span>
                                            </div>
                                        </div>

                                        <div class="mt-4 rounded-lg bg-blue-50 border border-blue-100 px-4 py-3 flex items-center justify-between gap-3">
                                            <div>
                                                <p class="text-[11px] font-bold tracking-wide text-blue-700">JUMLAH BERSIH DITRANSFER KE KLIEN</p>
                                                <p class="text-xs text-blue-500">Net Payment Transfer</p>
                                            </div>
                                            <p class="text-lg font-bold text-blue-700 font-mono whitespace-nowrap" x-text="detailRow.bersihDisplay"></p>
                                        </div>
                                    </div>

                                    <div class="my-4 border-t border-slate-100"></div>

                                    {{-- D: Jurnal --}}
                                    <p class="text-xs font-bold text-slate-700 mb-2.5">D. CATATAN JURNAL KEUANGAN</p>
                                    <div class="rounded-lg bg-slate-950 p-4 font-mono text-xs space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <span class="text-emerald-400">(Dr) Beban Jasa / Operasional</span>
                                            <span class="text-white" x-text="detailRow.dppDisplay"></span>
                                        </div>
                                        <div class="flex items-center justify-between" x-show="detailRow.ppnRaw > 0">
                                            <span class="text-emerald-400">(Dr) PPN Masukan</span>
                                            <span class="text-white" x-text="formatIDR(detailRow.ppnRaw)"></span>
                                        </div>
                                        <div class="flex items-center justify-between pl-3">
                                            <span class="text-amber-400">(Cr) Hutang <span x-text="detailRow.jenisPphDisplay"></span></span>
                                            <span class="text-amber-300" x-text="detailRow.potonganDisplay"></span>
                                        </div>
                                        <div class="flex items-center justify-between pl-3">
                                            <span class="text-amber-400">(Cr) Kas / Bank Transfer</span>
                                            <span class="text-amber-300" x-text="detailRow.bersihDisplay"></span>
                                        </div>
                                    </div>

                                    <div class="my-4 border-t border-slate-100"></div>

                                    {{-- Footer: Penandatangan & Status --}}
                                    <div class="flex items-end justify-between gap-4 flex-wrap pb-1">
                                        <div>
                                            <p class="text-xs text-slate-500">Penandatangan: <span class="font-semibold text-slate-700" x-text="penandatangan.nama"></span></p>
                                            <p class="text-xs text-slate-400 mt-0.5">Jabatan: <span x-text="penandatangan.jabatan"></span></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs text-slate-500">
                                                Status: <span class="font-semibold" :class="detailRow.status === 'Sudah Dibayar & Dipotong' ? 'text-emerald-600' : 'text-blue-600'" x-text="detailRow.status"></span>
                                            </p>
                                            <p class="text-xs text-slate-400 mt-0.5">Tgl Catat: <span x-text="detailRow.tglCatat"></span></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ================= MODAL: KONFIRMASI HAPUS ================= --}}
                <div x-show="confirmDeleteId !== null" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center px-4"
                    style="background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <div @click.away="cancelDelete()"
                        class="bg-white rounded-2xl p-6 w-full max-w-sm text-center"
                        style="border: 1px solid #D9D9D9;"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">
                        <p class="text-base font-semibold text-slate-800 mb-6">Yakin ingin menghapus data ini?</p>
                        <div class="flex items-center justify-center gap-8">
                            <button type="button" @click="deleteConfirmed()" class="text-sm font-semibold" style="color:#DC2626">Hapus</button>
                            <button type="button" @click="cancelDelete()" class="text-sm font-semibold" style="color:#0F172A">Batal</button>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

</x-app-layout>