{{-- ============================================================
     FORM CARD 3 — Klaster PPh Unifikasi & Objek Pajak
     ============================================================ --}}
<div class="bg-white rounded-2xl border border-slate-200 p-6">

    <div class="flex items-start gap-3 pb-4 mb-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 8 4-8 4-8-4 8-4Z"/><path d="m4 11 8 4 8-4"/><path d="m4 15 8 4 8-4"/></svg>
        </div>
        <div>
            <h2 class="font-bold text-slate-900 leading-tight">3. Klaster PPh Unifikasi &amp; Objek Pajak</h2>
            <p class="text-sm text-slate-400">Pilih jenis potongan pajak yang sesuai dengan substansi transaksi tagihan</p>
        </div>
    </div>

    {{-- 5 Card Klaster PPh --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        <template x-for="k in klasterList" :key="k.code">
            <button type="button" @click="klasterSelected = k.code"
                class="rounded-xl border-2 px-3 py-3 text-left transition"
                :class="klasterSelected === k.code
                    ? 'border-blue-600'
                    : 'border-slate-200 hover:border-slate-300'"
                :style="klasterSelected === k.code ? 'background-color:#E8F0FE; border-color:#2563EB' : ''">
                <p class="text-sm font-bold" :class="klasterSelected === k.code ? 'text-blue-700' : 'text-slate-800'" x-text="k.label"></p>
                <p class="text-xs mt-0.5 truncate" :class="klasterSelected === k.code ? 'text-blue-600' : 'text-slate-400'" x-text="k.desc"></p>
            </button>
        </template>
    </div>

    {{-- Kode Objek Pajak (KOP) — custom dropdown dengan search --}}
    <div class="relative mb-1" @click.away="open.kop = false">
        <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">KODE OBJEK PAJAK (KOP)</label>

        <button type="button" @click="toggle('kop')"
            class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-left transition"
            :class="open.kop ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-800" x-text="selectedKop?.code ?? 'Pilih kode objek pajak'"></p>
                <p class="text-xs text-slate-400 truncate" x-text="selectedKop ? `[${selectedKop.code}] ${selectedKop.desc}` : ''"></p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.kop }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>

        {{-- Dropdown panel: sticky search + scrollable list --}}
        <div x-show="open.kop" x-cloak x-transition
            class="absolute left-0 right-0 z-30 mt-2 rounded-xl border border-slate-200 bg-white shadow-xl overflow-hidden">

            {{-- Sticky search bar --}}
            <div class="sticky top-0 bg-white p-2.5 border-b border-slate-100 z-10">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color:#94A3B8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        x-ref="kopSearchInput"
                        x-model="kopSearch"
                        placeholder="Search code or description"
                        class="w-full rounded-lg pl-9 pr-3 py-2 text-sm bg-white focus:outline-none"
                        style="border:1px solid #2563EB; color:#0f172a"
                        :style="'--tw-placeholder-color:#94A3B8'"
                    />
                </div>
            </div>

            {{-- Scrollable option list --}}
            <div class="max-h-64 overflow-y-auto py-1">
                <template x-for="k in filteredKopList" :key="k.code">
                    <button type="button" @click="selectKop(k.code)"
                        class="w-full flex items-start justify-between gap-2 py-2.5 text-left transition hover:bg-[#BFDBFE]"
                        style="padding-left:10px; padding-right:10px;">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold" :class="kopSelected === k.code ? 'text-blue-700' : 'text-slate-800'" x-text="k.code"></p>
                            <p class="text-xs text-slate-500 leading-snug" x-text="`[${k.code}] ${k.desc} (${k.tarif}%)`"></p>
                        </div>
                        <svg x-show="kopSelected === k.code" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" style="color:#2563EB" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </button>
                </template>

                {{-- Empty state --}}
                <div x-show="filteredKopList.length === 0" x-cloak class="px-4 py-8 text-center">
                    <p class="text-sm text-slate-400">Tidak ada kode objek pajak yang cocok.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Info box: deskripsi KOP terpilih --}}
    <div class="bg-slate-50 rounded-lg px-4 py-3 mb-5">
        <p class="text-sm font-semibold text-slate-700" x-text="selectedKop?.desc ?? ''"></p>
        <p class="text-xs text-slate-500 mt-0.5">
            Dasar Hukum: PMK-141/PMK.03/2015 &amp; PER-24/PJ/2021 &bull; Tarif Dasar: <span class="font-semibold text-blue-600" x-text="(selectedKop?.tarif ?? 0) + '%'"></span>
        </p>
    </div>

    {{-- Fasilitas Perpajakan / Surat Keterangan --}}
    <div class="relative" @click.away="open.fasilitas = false">
        <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">FASILITAS PERPAJAKAN / SURAT KETERANGAN</label>
        <button type="button" @click="toggle('fasilitas')"
            class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
            :class="open.fasilitas ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
            <span class="text-slate-800 truncate" x-text="fasilitasSelected"></span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.fasilitas }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
        </button>
        <div x-show="open.fasilitas" x-cloak x-transition
            class="absolute left-0 right-0 z-20 mt-2 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
            <template x-for="f in fasilitasList" :key="f">
                <button type="button" @click="fasilitasSelected = f; open.fasilitas = false"
                    class="w-full flex items-center justify-between gap-2 py-2 text-sm transition text-left hover:bg-[#BFDBFE]"
                    style="padding-left:10px; padding-right:10px;"
                    :class="fasilitasSelected === f ? 'text-blue-700 font-semibold' : 'text-slate-700'">
                    <span x-text="f"></span>
                    <svg x-show="fasilitasSelected === f" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" style="color:#2563EB" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </button>
            </template>
        </div>
    </div>

</div>