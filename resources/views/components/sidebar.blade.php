@props(['active' => ''])

@php
    $pengaturanChildren = ['pengaturan-pemotong-pajak', 'pengaturan-vendor', 'pengaturan-master-kop'];
    $pengaturanIsActive = in_array($active, $pengaturanChildren);

    $navItemClass = fn (string $key) => $active === $key
        ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium'
        : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-white/5 hover:text-white text-sm font-medium transition';
@endphp

<aside class="w-60 shrink-0 h-screen bg-slate-950 text-slate-300 flex flex-col justify-between"
    x-data="{ pengaturanOpen: {{ $pengaturanIsActive ? 'true' : 'false' }}, logoutConfirm: false }">
    <div>
        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="3" width="14" height="18" rx="2" />
                    <rect x="8" y="6" width="8" height="4" rx="0.5" />
                    <circle cx="8.5" cy="14" r="0.6" fill="currentColor" stroke="none" />
                    <circle cx="12" cy="14" r="0.6" fill="currentColor" stroke="none" />
                    <circle cx="15.5" cy="14" r="0.6" fill="currentColor" stroke="none" />
                    <circle cx="8.5" cy="17" r="0.6" fill="currentColor" stroke="none" />
                    <circle cx="12" cy="17" r="0.6" fill="currentColor" stroke="none" />
                    <circle cx="15.5" cy="17" r="0.6" fill="currentColor" stroke="none" />
                </svg>
            </div>
            <span class="font-bold text-white text-[15px] leading-tight">TaxCalc <span class="text-blue-500 font-bold">Unifikasi</span></span>
        </div>

        {{-- Nav --}}
        <nav class="px-3 py-4 space-y-1">
            <a href="{{ url('/dashboard') }}" class="{{ $navItemClass('dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>
            <a href="{{ url('/hitung-tagihan') }}" class="{{ $navItemClass('hitung-tagihan') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M9 7h6M9 11h.01M12 11h.01M15 11h.01M9 15h.01M12 15h.01M15 15h.01"/></svg>
                Hitung Tagihan
            </a>
            <a href="{{ url('/daftar-tagihan') }}" class="{{ $navItemClass('daftar-tagihan') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                Daftar Tagihan
            </a>
            <a href="{{ url('/rekapitulasi') }}" class="{{ $navItemClass('rekapitulasi') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 12 8.5 9.5M12 12l5-2"/></svg>
                Rekapitulasi
            </a>
            <a href="{{ url('/ekspor-laporan') }}" class="{{ $navItemClass('ekspor-laporan') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v13M12 16l-4-4M12 16l4-4"/><path d="M4 19h16"/></svg>
                Ekspor Laporan
            </a>
            <a href="{{ url('/asisten-pajak-ai') }}" class="{{ $navItemClass('asisten-pajak-ai') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 9h18"/></svg>
                Asisten Pajak AI
            </a>

            {{-- Pengaturan: dropdown / accordion --}}
            <div>
                <button
                    type="button"
                    @click="pengaturanOpen = !pengaturanOpen"
                    class="w-full {{ $pengaturanIsActive
                        ? 'flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium'
                        : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:bg-white/5 hover:text-white text-sm font-medium transition' }}"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-[18px] h-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span class="flex-1 text-left">Pengaturan</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 transition-transform" :class="{ 'rotate-180': pengaturanOpen }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <div x-show="pengaturanOpen" x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mt-1 ml-[30px] space-y-0.5 border-l border-white/10 pl-3">
                    <a href="{{ url('/pengaturan/pemotong-pajak') }}"
                        class="block px-2.5 py-2 rounded-md text-sm transition {{ $active === 'pengaturan-pemotong-pajak' ? 'text-blue-400 font-semibold bg-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Pemotong Pajak
                    </a>
                    <a href="{{ url('/pengaturan/vendor') }}"
                        class="block px-2.5 py-2 rounded-md text-sm transition {{ $active === 'pengaturan-vendor' ? 'text-blue-400 font-semibold bg-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Vendor
                    </a>
                    <a href="{{ url('/pengaturan/master-kop') }}"
                        class="block px-2.5 py-2 rounded-md text-sm transition {{ $active === 'pengaturan-master-kop' ? 'text-blue-400 font-semibold bg-white/5' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        Master KOP
                    </a>
                </div>
            </div>
        </nav>
    </div>

    {{-- User footer --}}
    <div class="border-t border-white/10 p-4">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold shrink-0">AD</div>
            <div class="min-w-0">
                <p class="text-sm text-white font-medium truncate">admin@gmail.com</p>
                <p class="text-xs text-slate-400 truncate">Tax Compliance &amp; Finance Staff</p>
            </div>
        </div>
        <button type="button" @click="logoutConfirm = true" class="w-full flex items-center justify-center gap-2 rounded-lg bg-white/5 hover:bg-white/10 text-slate-200 text-sm font-medium py-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            Keluar (Sign Out)
        </button>
    </div>

    {{-- ================= MODAL: KONFIRMASI LOGOUT ================= --}}
    <div x-show="logoutConfirm" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px);"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div @click.away="logoutConfirm = false"
            class="bg-white rounded-2xl p-6 w-full max-w-sm text-center"
            style="border: 1px solid #D9D9D9;"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">
            <p class="text-base font-semibold text-slate-800 mb-6">Yakin ingin keluar?</p>
            <div class="flex items-center justify-center gap-8">
                <button type="button" @click="window.location.href = '{{ url('/login') }}'" class="text-sm font-semibold" style="color:#DC2626">Keluar</button>
                <button type="button" @click="logoutConfirm = false" class="text-sm font-semibold" style="color:#0F172A">Batal</button>
            </div>
        </div>
    </div>
</aside>