{{-- ============================================================
     FORM CARD 2 — Identitas Klien / Vendor Lawan Transaksi
     ============================================================ --}}
<div class="bg-white rounded-2xl border border-slate-200 p-6">

    <div class="flex items-start gap-3 pb-4 mb-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/></svg>
        </div>
        <div>
            <h2 class="font-bold text-slate-900 leading-tight">2. Identitas Klien / Vendor Lawan Transaksi</h2>
            <p class="text-sm text-slate-400">Kategori wajib pajak dan status kepemilikan NPWP</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Nama Klien / Vendor --}}
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NAMA KLIEN / VENDOR</label>
            <input type="text" x-model="namaKlien" placeholder="Contoh: PT Solusi Teknologi Prima"
                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
        </div>

        {{-- Kategori Wajib Pajak --}}
        <div class="relative" @click.away="open.kategoriWajibPajak = false">
            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">KATEGORI WAJIB PAJAK</label>
            <button type="button" @click="toggle('kategoriWajibPajak')"
                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                :class="open.kategoriWajibPajak ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                <span class="text-slate-800 truncate" x-text="kategoriWajibPajakSelected"></span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.kategoriWajibPajak }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div x-show="open.kategoriWajibPajak" x-cloak x-transition
                class="absolute left-0 right-0 z-20 mt-2 rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                <template x-for="k in kategoriWajibPajakList" :key="k">
                    <button type="button" @click="kategoriWajibPajakSelected = k; open.kategoriWajibPajak = false"
                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                        :class="kategoriWajibPajakSelected === k ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                        <span x-text="k"></span>
                        <svg x-show="kategoriWajibPajakSelected === k" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </button>
                </template>
            </div>
        </div>

        {{-- NPWP / NIK + Checkbox Non-NPWP --}}
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-semibold tracking-wide text-slate-500">NPWP / NIK (16 DIGIT)</label>
                <label class="flex items-center gap-1.5 cursor-pointer select-none">
                    <input type="checkbox" x-model="nonNpwp"
                        class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500/40 cursor-pointer" />
                    <span class="text-xs font-medium transition" :class="nonNpwp ? 'text-red-600' : 'text-slate-500'">Non-NPWP (+100%)</span>
                </label>
            </div>

            {{-- Input normal (tampil kalau NPWP diisi seperti biasa) --}}
            <input
                type="text"
                x-show="!nonNpwp"
                x-model="npwp"
                maxlength="20"
                placeholder="01.234.567.8-000.000"
                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />

            {{-- Rectangle merah (tampil kalau Non-NPWP dicentang) --}}
            <div
                x-show="nonNpwp"
                x-cloak
                class="w-full rounded-lg px-3.5 py-2.5 text-sm font-semibold text-red-600"
                style="background-color:#FEF2F2; border:1px solid #B91C1C;">
                NON-NPWP (Tarif 2x)
            </div>
        </div>

    </div>
</div>