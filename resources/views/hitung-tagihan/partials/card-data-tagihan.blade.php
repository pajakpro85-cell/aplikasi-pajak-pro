{{-- ============================================================
     FORM CARD 1 — Data Tagihan / Invoice Klien
     ============================================================ --}}
<div class="bg-white rounded-2xl border border-slate-200 p-6">

    <div class="flex items-start gap-3 pb-4 mb-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h9l5 5v13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h6M9 9h2"/></svg>
        </div>
        <div>
            <h2 class="font-bold text-slate-900 leading-tight">1. Data Tagihan / Invoice Klien</h2>
            <p class="text-sm text-slate-400">Nomor tagihan, tanggal terbit, dan masa pembukuan pajak</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Nomor Invoice --}}
        <div>
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NOMOR INVOICE / TAGIHAN</label>
            <input type="text" x-model="nomorInvoice" placeholder="Contoh: INV/2026/08/01"
                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
        </div>

        {{-- Tanggal Invoice: DATE PICKER --}}
        <div class="relative" @click.away="open.calendar = false">
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">TANGGAL INVOICE</label>
            <button type="button" @click="toggle('calendar')"
                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                :class="open.calendar ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                <span :class="selectedDate ? 'text-slate-800' : 'text-slate-400'" x-text="selectedDate ? tanggalInvoiceDisplay : 'Pilih tanggal'"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            </button>

            {{-- Calendar popup --}}
            <div x-show="open.calendar" x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="absolute left-0 z-30 mt-2 w-72 rounded-xl border border-slate-200 bg-white shadow-xl p-4 origin-top-left">

                {{-- Header: prev / month-year / next --}}
                <div class="flex items-center justify-between mb-3">
                    <button type="button" @click="prevMonth()" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-slate-100 text-slate-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>

                    <div class="flex items-center gap-1.5">
                        <select x-model.number="viewMonth" class="text-sm font-semibold text-slate-800 bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer py-0 pr-6">
                            <template x-for="(m, idx) in monthNames" :key="idx">
                                <option :value="idx" x-text="m"></option>
                            </template>
                        </select>
                        <select x-model.number="viewYear" class="text-sm font-semibold text-slate-800 bg-transparent border-none focus:outline-none focus:ring-0 cursor-pointer py-0 pr-5">
                            <template x-for="y in calendarYearOptions" :key="y">
                                <option :value="y" x-text="y"></option>
                            </template>
                        </select>
                    </div>

                    <button type="button" @click="nextMonth()" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-slate-100 text-slate-500 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>

                {{-- Day-of-week labels --}}
                <div class="grid grid-cols-7 gap-y-1 mb-1">
                    <template x-for="d in dayNames" :key="d">
                        <div class="text-center text-[11px] font-semibold text-slate-400" x-text="d"></div>
                    </template>
                </div>

                {{-- Days grid --}}
                <div class="grid grid-cols-7 gap-y-1">
                    <template x-for="(day, idx) in calendarDays" :key="idx">
                        <div class="flex items-center justify-center">
                            <button
                                type="button"
                                x-show="day !== null"
                                @click="pickDay(day)"
                                x-text="day"
                                class="w-8 h-8 rounded-full text-sm transition"
                                :class="{
                                    'bg-blue-600 text-white font-semibold': isSelectedDay(day),
                                    'text-blue-600 font-semibold ring-1 ring-inset ring-blue-300': isToday(day) && !isSelectedDay(day),
                                    'text-slate-700 hover:bg-slate-100': !isSelectedDay(day) && !isToday(day),
                                }"
                            ></button>
                        </div>
                    </template>
                </div>

                {{-- Footer: Clear / Today --}}
                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                    <button type="button" @click="clearDate()" class="text-sm font-medium" style="color:#2563EB">Clear</button>
                    <button type="button" @click="setToday()" class="text-sm font-medium" style="color:#2563EB">Today</button>
                </div>
            </div>
        </div>

        {{-- Masa & Tahun Pajak --}}
        <div>
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">MASA &amp; TAHUN PAJAK</label>
            <div class="flex items-center gap-3">

                {{-- Dropdown Bulan --}}
                <div class="relative flex-1" @click.away="open.masaBulan = false">
                    <button type="button" @click="toggle('masaBulan')"
                        class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                        :class="open.masaBulan ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                        <span class="text-slate-800 truncate" x-text="masaBulanSelected.split(' (')[0]"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.masaBulan }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open.masaBulan" x-cloak x-transition
                        class="absolute left-0 z-20 mt-2 w-56 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                        <template x-for="m in masaBulanList" :key="m">
                            <button type="button" @click="masaBulanSelected = m; open.masaBulan = false"
                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition"
                                :class="masaBulanSelected === m ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                <span x-text="m"></span>
                                <svg x-show="masaBulanSelected === m" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Dropdown Tahun --}}
                <div class="relative w-28" @click.away="open.tahunPajak = false">
                    <button type="button" @click="toggle('tahunPajak')"
                        class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                        :class="open.tahunPajak ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                        <span class="text-slate-800" x-text="tahunPajakSelected"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.tahunPajak }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div x-show="open.tahunPajak" x-cloak x-transition
                        class="absolute left-0 z-20 mt-2 w-24 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                        <template x-for="y in tahunPajakList" :key="y">
                            <button type="button" @click="tahunPajakSelected = y; open.tahunPajak = false"
                                class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition"
                                :class="tahunPajakSelected === y ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                <span x-text="y"></span>
                                <svg x-show="tahunPajakSelected === y" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nilai Tagihan Pokok / DPP --}}
        <div>
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NILAI TAGIHAN POKOK / DPP (IDR)</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-sm font-medium text-slate-400">Rp</span>
                <input
                    type="text"
                    inputmode="numeric"
                    placeholder="0"
                    :value="formatNumber(nilaiDPP)"
                    @input="onNilaiDPPInput($event)"
                    class="w-full rounded-lg border border-slate-200 pl-9 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
            </div>
        </div>

        {{-- Perlakuan PPN Faktur Masukan --}}
        <div class="relative" @click.away="open.perlakuanPPN = false">
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">PERLAKUAN PPN FAKTUR MASUKAN</label>
            <button type="button" @click="toggle('perlakuanPPN')"
                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                :class="open.perlakuanPPN ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                <span class="text-slate-800" x-text="perlakuanPPN"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.perlakuanPPN }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open.perlakuanPPN" x-cloak x-transition
                class="absolute left-0 right-0 z-20 mt-2 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                <template x-for="p in perlakuanPPNList" :key="p">
                    <button type="button" @click="perlakuanPPN = p; open.perlakuanPPN = false"
                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition"
                        :class="perlakuanPPN === p ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                        <span x-text="p"></span>
                        <svg x-show="perlakuanPPN === p" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </button>
                </template>
            </div>
        </div>

        {{-- Status Pembayaran --}}
        <div class="relative" @click.away="open.statusPembayaran = false">
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">STATUS PEMBAYARAN</label>
            <button type="button" @click="toggle('statusPembayaran')"
                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                :class="open.statusPembayaran ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                <span class="text-slate-800 truncate" x-text="statusPembayaranSelected"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.statusPembayaran }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open.statusPembayaran" x-cloak x-transition
                class="absolute left-0 right-0 z-20 mt-2 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                <template x-for="s in statusPembayaranList" :key="s">
                    <button type="button" @click="statusPembayaranSelected = s; open.statusPembayaran = false"
                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition"
                        :class="statusPembayaranSelected === s ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                        <span x-text="s"></span>
                        <svg x-show="statusPembayaranSelected === s" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </button>
                </template>
            </div>
        </div>

    </div>
</div>