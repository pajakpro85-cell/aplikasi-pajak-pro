<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="pengaturan-master-kop" />

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

            {{-- ---- MAIN CONTENT (scroll vertikal untuk halaman; tabel di dalam punya scroll sendiri) ---- --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="pengaturanMasterKopPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Pengaturan Aplikasi e-Bupot Unifikasi</h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola profil pemotong pajak, data penandatangan bukti potong, dan master objek pajak</p>
                </div>

                {{-- ================= CARD: Master KOP & Tarif Default ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10" @click.away="closeAll()">

                    {{-- Header --}}
                    <div class="flex items-center justify-between gap-4 flex-wrap px-6 pt-6 pb-5 border-b border-slate-100">
                        <div>
                            <h2 class="font-bold text-slate-900">Master Kode Objek Pajak (KOP) &amp; Tarif Default</h2>
                            <p class="text-sm text-slate-400 mt-0.5">Tarif acuan PPh 23, PPh 4(2), PPh 22, PPh 15, dan PPh 26</p>
                        </div>
                        <button type="button" @click="resetToDefault()"
                            class="inline-flex items-center gap-2 rounded-lg border border-blue-200 hover:bg-blue-50 transition text-blue-600 text-sm font-semibold px-4 py-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 1 3 6.7"/><path d="M3 4v6h6"/></svg>
                            Reset ke Default Standar
                        </button>
                    </div>

                    {{-- Search + Filter --}}
                    <div class="flex items-center gap-3 flex-wrap px-6 py-4 border-b border-slate-100">
                        <div class="relative flex-1 min-w-[240px]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" x-model="search" placeholder="Cari kode objek, nama atau transaksi"
                                class="w-full rounded-lg border border-slate-200 pl-10 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        {{-- Dropdown: Jenis PPh (styling konsisten dengan dropdown filter di halaman lain) --}}
                        <div class="relative">
                            <button type="button" @click="toggle('jenisPph')"
                                class="flex items-center gap-2 rounded-lg border px-3.5 py-2.5 text-sm text-left transition whitespace-nowrap"
                                :class="open.jenisPph || filterJenisPph !== 'Semua Jenis Pph' ? 'border-blue-500 text-blue-700 bg-blue-50/50' : 'border-slate-200 text-slate-700 hover:border-slate-300'">
                                <span x-text="filterJenisPph"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.jenisPph }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.jenisPph" x-cloak x-transition
                                class="absolute right-0 z-20 mt-2 w-56 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
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
                    </div>

                    {{-- Tabel: scroll VERTIKAL (header ikut nempel/sticky), sekaligus scroll horizontal untuk layar sempit --}}
                    <div class="overflow-y-auto overflow-x-auto" style="max-height: 65vh;">
                        <table class="w-full text-sm min-w-[900px]">
                            <thead class="sticky top-0 z-10" style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-200">
                                    <th class="px-6 py-3 whitespace-nowrap">KODE OBJEK</th>
                                    <th class="px-6 py-3 whitespace-nowrap">KLASTER</th>
                                    <th class="px-6 py-3 whitespace-nowrap">NAMA OBJEK &amp; DESKRIPSI</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-right">TARIF DASAR (%)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in filteredRows" :key="row.kode + row.nama">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4 align-top">
                                            <p class="font-semibold text-blue-600 font-mono whitespace-nowrap" x-text="row.kode"></p>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-medium px-2.5 py-1 whitespace-nowrap" x-text="row.klaster"></span>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <p class="font-semibold text-slate-800" x-text="row.nama"></p>
                                            <p class="text-xs text-slate-400 mt-0.5" x-text="row.deskripsi"></p>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="flex items-center justify-end gap-2">
                                                <input type="number" step="0.1" x-model.number="row.tarif"
                                                    class="w-20 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-800 text-right focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                                                <span class="text-sm text-slate-500">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filteredRows.length === 0">
                                    <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-400">
                                        Tidak ada kode objek pajak yang cocok dengan pencarian/filter kamu.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </main>
        </div>
    </div>

</x-app-layout>