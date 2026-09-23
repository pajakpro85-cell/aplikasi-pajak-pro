<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        {{-- ================= SIDEBAR (reusable component, statis di-scroll) ================= --}}
        <x-sidebar active="hitung-tagihan" />

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
                  x-data="hitungTagihanForm()" x-init="initDatePicker(); initFromQuery()">

                {{-- Page heading --}}
                <div class="flex items-start justify-between gap-4 flex-wrap mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Kalkulator Invoice Klien</h1>
                        <p class="text-sm text-slate-500 mt-1">Hitung potongan PPh Unifikasi (PPh 23, 4(2), 22, 15, 26) dan tentukan jumlah bersih yang harus ditransfer ke klien</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button" @click="resetForm()"
                            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition text-slate-700 text-sm font-semibold px-4 py-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 1 3 6.7"/><path d="M3 4v6h6"/></svg>
                            Reset Form
                        </button>
                        <button type="button"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                            Simpan Perhitungan Invoice
                        </button>
                    </div>
                </div>

                {{-- ================= GRID: FORM (kiri) + RINGKASAN (kanan) ================= --}}
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_420px] gap-6 items-start pb-10">

                    {{-- ======================= KOLOM KIRI: FORM CARDS ======================= --}}
                    <div class="space-y-6">

                        @include('hitung-tagihan.partials.card-data-tagihan')
                        @include('hitung-tagihan.partials.card-identitas-klien')
                        @include('hitung-tagihan.partials.card-klaster-pph')

                    </div>

                    {{-- ======================= KOLOM KANAN: RINGKASAN ======================= --}}
                    @include('hitung-tagihan.partials.panel-ringkasan')

                </div>

            </main>
        </div>
    </div>

</x-app-layout>