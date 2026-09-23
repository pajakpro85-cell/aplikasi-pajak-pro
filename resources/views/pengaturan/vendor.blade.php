<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="pengaturan-vendor" />

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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="pengaturanVendorPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Pengaturan Aplikasi e-Bupot Unifikasi</h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola profil pemotong pajak, data penandatangan bukti potong, dan master objek pajak</p>
                </div>

                {{-- ================= CARD FORM: Profil Vendor ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6">

                    <div class="flex items-center justify-between gap-4 flex-wrap pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/></svg>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-900 leading-tight">
                                    Profil Vendor
                                    <span x-show="editingId" x-cloak class="ml-2 text-xs font-semibold text-blue-600 align-middle">(sedang mengedit)</span>
                                </h2>
                                <p class="text-sm text-slate-400">Identitas Vendor yang tertera pada Bukti Potong dan SPT Masa</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" x-show="editingId" x-cloak @click="resetForm()"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition text-slate-600 text-sm font-semibold px-4 py-2.5">
                                Batal Edit
                            </button>
                            <button type="button" @click="saveProfile()"
                                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-5 py-2.5">
                                <span x-text="editingId ? 'Update Profil' : 'Simpan Profil'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NAMA VENDOR</label>
                            <input type="text" x-model="form.nama" placeholder="Contoh: PT Graha Pratama Solusindo"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">EMAIL</label>
                            <input type="email" x-model="form.email" placeholder="nama@email.com"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">ALAMAT LENGKAP VENDOR</label>
                            <input type="text" x-model="form.alamat" placeholder="Gedung Menara Sudirman Lt. 14, Jl. Jend. Sudirman Kav. 60, Jakarta Selatan"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>
                    </div>
                </div>

                {{-- ================= SEARCH BAR + TABEL: DAFTAR VENDOR (satu card, scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">

                    <div class="p-4 border-b border-slate-100">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" x-model="search" placeholder="Cari Nama Vendor, Alamat, Email"
                                class="w-full rounded-lg border border-slate-200 pl-10 pr-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[900px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">NAMA VENDOR</th>
                                    <th class="px-6 py-3 whitespace-nowrap">EMAIL</th>
                                    <th class="px-6 py-3 whitespace-nowrap">ALAMAT LENGKAP VENDOR</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in filteredVendors" :key="row.id">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-blue-600 whitespace-nowrap" x-text="row.nama"></p>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap" x-text="row.email || '\u2013'"></td>
                                        <td class="px-6 py-4 text-slate-600 max-w-md" x-text="row.alamat"></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-3 text-slate-400">
                                                <button type="button" title="Edit" @click="editVendor(row)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                                </button>
                                                <button type="button" title="Duplikat" @click="copyVendor(row)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                                </button>
                                                <button type="button" title="Hapus" @click="askDelete(row.id)" class="hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filteredVendors.length === 0">
                                    <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-400">
                                        Tidak ada vendor yang cocok dengan pencarian kamu.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        <p class="text-base font-semibold text-slate-800 mb-6">Yakin ingin menghapus vendor ini?</p>
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