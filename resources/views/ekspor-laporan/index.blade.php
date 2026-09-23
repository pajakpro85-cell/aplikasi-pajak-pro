<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="ekspor-laporan" />

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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="eksporLaporanPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Ekspor Laporan Tagihan &amp; Pajak</h1>
                    <p class="text-sm text-slate-500 mt-1">Unduh rekapitulasi perhitungan tagihan invoice, potongan PPh, dan daftar transfer bersih</p>
                </div>

                {{-- ================= CARD UTAMA ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 max-w-2xl" @click.away="closeAll()">

                    {{-- Pilih Masa & Tahun Pembukuan --}}
                    <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">PILIH MASA &amp; TAHUN PEMBUKUAN</label>
                    <div class="grid grid-cols-2 gap-4 mb-7">

                        {{-- Dropdown Masa --}}
                        <div class="relative">
                            <button type="button" @click="toggle('masa')"
                                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                                :class="open.masa ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                                <span class="text-slate-800" x-text="masaSelected"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.masa }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.masa" x-cloak x-transition
                                class="absolute left-0 z-20 mt-2 w-56 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
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

                        {{-- Tahun Pembukuan: stepper dengan segitiga atas/bawah --}}
                        <div class="w-full flex items-center justify-between rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm bg-white">
                            <span class="text-slate-800" x-text="tahunPembukuan"></span>
                            <div class="flex flex-col gap-1">
                                <button type="button" @click="incrementTahun()" title="Tahun berikutnya" class="text-slate-400 hover:text-blue-600 transition leading-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6" class="w-2.5 h-1.5 fill-current"><polygon points="5,0 10,6 0,6" /></svg>
                                </button>
                                <button type="button" @click="decrementTahun()" title="Tahun sebelumnya" class="text-slate-400 hover:text-blue-600 transition leading-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 6" class="w-2.5 h-1.5 fill-current"><polygon points="0,0 10,0 5,6" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Format Ekspor --}}
                    <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">FORMAT EKSPOR</label>
                    <div class="grid grid-cols-2 gap-4 mb-6">

                        {{-- Card: Rekap Tagihan (CSV/Excel) --}}
                        <button type="button" @click="formatSelected = 'excel'"
                            class="rounded-xl border-2 px-4 py-6 text-center transition"
                            :class="formatSelected === 'excel' ? 'border-emerald-500' : 'border-slate-200 hover:border-slate-300'"
                            :style="formatSelected === 'excel' ? 'background-color:#ECFDF5' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-3" :style="formatSelected === 'excel' ? 'color:#059669' : 'color:#0F172A'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/>
                                <path d="M14 2v6h6"/>
                            </svg>
                            <p class="font-bold" :class="formatSelected === 'excel' ? 'text-emerald-700' : 'text-slate-900'">Rekap Tagihan (csv / Excel)</p>
                            <p class="text-xs mt-1" :class="formatSelected === 'excel' ? 'text-emerald-600' : 'text-slate-400'">Format Rekonsiliasi Financce</p>
                        </button>

                        {{-- Card: Daftar Tagihan Cetak (PDF) --}}
                        <button type="button" @click="formatSelected = 'pdf'"
                            class="rounded-xl border-2 px-4 py-6 text-center transition"
                            :class="formatSelected === 'pdf' ? 'border-blue-500' : 'border-slate-200 hover:border-slate-300'"
                            :style="formatSelected === 'pdf' ? 'background-color:#EFF6FF' : ''">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-3" :style="formatSelected === 'pdf' ? 'color:#2563EB' : 'color:#0F172A'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9V2h12v7"/>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                <rect x="6" y="14" width="12" height="8"/>
                            </svg>
                            <p class="font-bold" :class="formatSelected === 'pdf' ? 'text-blue-700' : 'text-slate-900'">Daftar Tagihan Cetak (PDF)</p>
                            <p class="text-xs mt-1" :class="formatSelected === 'pdf' ? 'text-blue-600' : 'text-slate-400'">Lembar Approval / Voucher</p>
                        </button>
                    </div>

                    {{-- Tombol Unduh --}}
                    <button type="button" @click="unduhLaporan()"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold py-3 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 13v8m0 0-4-4m4 4 4-4"/><path d="M20.4 17.5A5.5 5.5 0 0 0 18 7h-1.3A7 7 0 1 0 4 14.9"/></svg>
                        <span>Unduh Laporan Masa <span x-text="masaLabelPendek"></span> <span x-text="tahunPembukuan"></span></span>
                    </button>

                    {{-- Info Box --}}
                    <div class="rounded-xl px-4 py-4 flex items-start gap-3" style="background-color:#EFF6FF">
                        <div class="shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#2563EB" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2 4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4Z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <p class="text-sm text-blue-900 leading-relaxed">
                            <span class="font-bold">Akurat &amp; Siap Pembukuan</span>: Berkas ekspor memuat rincian lengkap nomor invoice klien, DPP, PPN masukan, tarif PPh, potongan pajak, metode (normal / gross up), dan nilai bersih transfer ke rekening klien.
                        </p>
                    </div>

                </div>

            </main>
        </div>
    </div>

</x-app-layout>