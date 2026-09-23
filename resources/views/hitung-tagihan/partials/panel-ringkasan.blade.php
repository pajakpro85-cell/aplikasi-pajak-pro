{{-- ============================================================
     PANEL RINGKASAN — Potongan Tagihan Klien (live calculation)
     ============================================================ --}}
<div class="rounded-2xl p-6 xl:sticky xl:top-0" style="background-color:#0f172a">

    <div class="flex items-center justify-between mb-1">
        <p class="text-xs font-semibold tracking-wide text-blue-400">RINGKASAN PERHITUNGAN</p>
        <span class="inline-flex items-center rounded-full bg-blue-600/20 text-blue-300 text-xs font-medium px-3 py-1 border border-blue-500/30">
            PPh Pasal <span x-text="selectedKlaster.code"></span>
        </span>
    </div>
    <h2 class="text-lg font-bold text-white mb-6">Potongan Tagihan Klien</h2>

    {{-- Group 1 --}}
    <div class="space-y-2.5 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-slate-400">Nilai Pokok Tagihan (DPP)</span>
            <span class="font-mono font-semibold text-white" x-text="formatIDR(nilaiDPP)"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-400">PPN Masukan 11%</span>
            <span class="font-mono font-semibold text-emerald-400" x-text="'+ ' + formatIDR(ppnMasukan)"></span>
        </div>
    </div>

    <div class="my-4 border-t border-white/10"></div>

    {{-- Group 2 --}}
    <div class="space-y-2.5 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-slate-400">Total Tagihan (DPP + PPN)</span>
            <span class="font-mono font-semibold text-white" x-text="formatIDR(totalTagihan)"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-slate-400">Potongan PPh Pasal <span x-text="selectedKlaster.code"></span></span>
            <span class="font-mono font-semibold text-red-400" x-text="'- ' + formatIDR(potonganPPh)"></span>
        </div>
    </div>

    {{-- Highlight: Jumlah Bersih --}}
    <div class="mt-5 rounded-xl bg-blue-600 px-5 py-4">
        <p class="text-xs font-semibold tracking-wide text-blue-100">JUMLAH BERSIH DITRANSFER KLIEN</p>
        <p class="text-2xl font-bold text-white font-mono mt-1" x-text="formatIDR(bersihDitransfer)"></p>
        <p class="text-xs text-blue-100 mt-1">Jumlah setelah dipotong PPh dan ditambahkan PPN (bila ada)</p>
    </div>

    {{-- Simulasi Jurnal --}}
    <p class="text-xs font-semibold tracking-wide text-slate-400 mt-6 mb-2">SIMULASI JURNAL AKUNTANSI (FINANCE VOUCHER)</p>
    <div class="rounded-lg bg-black/40 p-4 font-mono text-xs space-y-1.5">
        <div class="flex items-center justify-between">
            <span class="text-emerald-400">(Dr) Beban Jasa / Biaya</span>
            <span class="text-white" x-text="formatIDR(nilaiDPP)"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-emerald-400">(Dr) PPN Masukan</span>
            <span class="text-white" x-text="formatIDR(ppnMasukan)"></span>
        </div>
        <div class="flex items-center justify-between pl-3">
            <span class="text-amber-400">(Cr) Hutang PPh Pasal <span x-text="selectedKlaster.code"></span></span>
            <span class="text-amber-300" x-text="formatIDR(potonganPPh)"></span>
        </div>
        <div class="flex items-center justify-between pl-3">
            <span class="text-amber-400">(Cr) Kas / Bank Transfer</span>
            <span class="text-amber-300" x-text="formatIDR(bersihDitransfer)"></span>
        </div>
    </div>

    {{-- Simpan --}}
    <button type="button" class="w-full mt-6 inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold py-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
        Simpan ke Rekapitulasi Tagihan
    </button>
</div>