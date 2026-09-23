<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="pengaturan-pemotong-pajak" />

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
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6" x-data="pengaturanPemotongPajakPage()">

                {{-- Page heading --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-slate-900">Pengaturan Aplikasi e-Bupot Unifikasi</h1>
                    <p class="text-sm text-slate-500 mt-1">Kelola profil pemotong pajak, data penandatangan bukti potong, dan master objek pajak</p>
                </div>

                {{-- ================= CARD FORM: Profil Pemotong / Pemungut Pajak ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-6" @click.away="closeAll()">

                    <div class="flex items-center justify-between gap-4 flex-wrap pb-4 mb-5 border-b border-slate-100">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/></svg>
                            </div>
                            <div>
                                <h2 class="font-bold text-slate-900 leading-tight">
                                    Profil Pemotong / Pemungut pajak
                                    <span x-show="editingId" x-cloak class="ml-2 text-xs font-semibold text-blue-600 align-middle">(sedang mengedit)</span>
                                </h2>
                                <p class="text-sm text-slate-400">Identitas perusahaan yang tertera pada Bukti Potong dan SPT Masa</p>
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
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NAMA PERUSAHAAN / INSTANSI PEMOTONG</label>
                            <input type="text" x-model="form.namaPerusahaan" placeholder="Contoh: PT Nusantara Sinergi Pratama"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NPWP / NITKU PEMOTONG (16 / 15 DIGIT)</label>
                            <input type="text" x-model="form.npwpNitku" placeholder="01.234.567.8-012.000"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-blue-600 font-mono placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">ALAMAT LENGKAP PEMOTONG PAJAK</label>
                            <input type="text" x-model="form.alamat" placeholder="Jl. Jenderal Sudirman Kav. 52-53, Senayan, Kebayoran Baru, Jakarta Selatan 12190"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">NAMA PEJABAT PENANDATANGAN BUPOT</label>
                            <input type="text" x-model="form.namaPejabat" placeholder="Contoh: Hendra Setiawan, S.E., Ak."
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">JABATAN PENANDATANGAN</label>
                            <input type="text" x-model="form.jabatan" placeholder="Contoh: Finance & Tax Manager"
                                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition" />
                        </div>

                        {{-- Metode Default Perhitungan --}}
                        <div class="relative">
                            <label class="block text-xs font-semibold tracking-wide text-slate-500 mb-2">METODE DEFAULT PERHITUNGAN</label>
                            <button type="button" @click="toggle('metode')"
                                class="w-full flex items-center justify-between rounded-lg border px-3.5 py-2.5 text-sm text-left transition"
                                :class="open.metode ? 'border-blue-500 ring-2 ring-blue-500/30' : 'border-slate-200 hover:border-slate-300'">
                                <span class="text-slate-800" x-text="form.metodeDefault"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open.metode }" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div x-show="open.metode" x-cloak x-transition
                                class="absolute left-0 right-0 z-20 mt-2 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white shadow-lg py-1.5">
                                <template x-for="m in masaBulanList" :key="m">
                                    <button type="button" @click="form.metodeDefault = m; open.metode = false"
                                        class="w-full flex items-center justify-between gap-2 px-3.5 py-2 text-sm hover:bg-blue-50 transition text-left"
                                        :class="form.metodeDefault === m ? 'text-blue-600 font-semibold' : 'text-slate-700'">
                                        <span x-text="m"></span>
                                        <svg x-show="form.metodeDefault === m" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ================= TABEL: DAFTAR PROFIL PEMOTONG PAJAK (scroll horizontal) ================= --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden mb-10">
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="font-bold text-slate-900">Daftar Profil Pemotong Pajak</h2>
                        <p class="text-sm text-slate-400 mt-0.5">Semua profil perusahaan/instansi pemotong yang tersimpan di sistem</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[1200px]">
                            <thead style="background-color:#F8FAFC;">
                                <tr class="text-left text-xs font-bold tracking-wide text-slate-500 border-b border-slate-100">
                                    <th class="px-6 py-3 whitespace-nowrap">NAMA PERUSAHAAN / INSTANSI</th>
                                    <th class="px-6 py-3 whitespace-nowrap">NPWP / NITKU</th>
                                    <th class="px-6 py-3 whitespace-nowrap">ALAMAT</th>
                                    <th class="px-6 py-3 whitespace-nowrap">PEJABAT PENANDATANGAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap">JABATAN</th>
                                    <th class="px-6 py-3 whitespace-nowrap">METODE DEFAULT</th>
                                    <th class="px-6 py-3 whitespace-nowrap text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="row in profiles" :key="row.id">
                                    <tr class="hover:bg-slate-50/60 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background-color:#BFDBFE">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" style="color:#1042AE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/></svg>
                                                </div>
                                                <p class="font-semibold text-slate-800 whitespace-nowrap" x-text="row.namaPerusahaan"></p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-mono text-blue-600 whitespace-nowrap" x-text="row.npwpNitku"></td>
                                        <td class="px-6 py-4 text-slate-500 max-w-xs truncate" x-text="row.alamat"></td>
                                        <td class="px-6 py-4 text-slate-700 whitespace-nowrap" x-text="row.namaPejabat"></td>
                                        <td class="px-6 py-4 text-slate-500 whitespace-nowrap" x-text="row.jabatan"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1" x-text="row.metodeDefault"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-3 text-slate-400">
                                                <button type="button" title="Lihat Detail" @click="viewProfile(row.id)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                                <button type="button" title="Edit" @click="editProfile(row)" class="hover:text-blue-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                                </button>
                                                <button type="button" title="Hapus" @click="askDelete(row.id)" class="hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L4 6"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="profiles.length === 0">
                                    <td colspan="7" class="px-6 py-16 text-center text-sm text-slate-400">
                                        Belum ada profil pemotong pajak yang tersimpan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ================= MODAL: VIEW DETAIL (read-only) ================= --}}
                <div x-show="viewRow !== null" x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center px-4"
                    style="background-color: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px);"
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">

                    <div @click.away="closeView()"
                        class="bg-white rounded-2xl w-full max-w-lg overflow-hidden"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <template x-if="viewRow">
                            <div>
                                {{-- Header --}}
                                <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background-color:#BFDBFE">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color:#1042AE" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="1"/><path d="M9 22v-4h6v4M9 6h.01M9 10h.01M9 14h.01M15 6h.01M15 10h.01M15 14h.01"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-semibold tracking-wide text-slate-400">DETAIL PROFIL PEMOTONG PAJAK</p>
                                            <h2 class="font-bold text-slate-900 truncate" x-text="viewRow.namaPerusahaan"></h2>
                                        </div>
                                    </div>
                                    <button type="button" @click="closeView()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 transition text-slate-400 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                {{-- Body --}}
                                <div class="px-6 py-5 space-y-4 text-sm">
                                    <div>
                                        <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">NPWP / NITKU PEMOTONG</p>
                                        <p class="font-mono text-blue-600" x-text="viewRow.npwpNitku"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">ALAMAT LENGKAP</p>
                                        <p class="text-slate-700 leading-relaxed" x-text="viewRow.alamat"></p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">PEJABAT PENANDATANGAN</p>
                                            <p class="text-slate-700" x-text="viewRow.namaPejabat"></p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">JABATAN</p>
                                            <p class="text-slate-700" x-text="viewRow.jabatan"></p>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold tracking-wide text-slate-400 mb-1">METODE DEFAULT PERHITUNGAN</p>
                                        <span class="inline-flex items-center rounded-md bg-slate-100 text-slate-600 text-xs font-medium px-2.5 py-1" x-text="viewRow.metodeDefault"></span>
                                    </div>
                                </div>

                                {{-- Footer --}}
                                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-slate-100 bg-slate-50">
                                    <button type="button" @click="closeView()" class="text-sm font-semibold text-slate-500 hover:text-slate-700 transition">Tutup</button>
                                    <button type="button" @click="editProfile(viewRow); closeView()"
                                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 hover:bg-blue-700 transition text-white text-sm font-semibold px-4 py-2">
                                        Edit Profil Ini
                                    </button>
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
                        <p class="text-base font-semibold text-slate-800 mb-6">Yakin ingin menghapus profil ini?</p>
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