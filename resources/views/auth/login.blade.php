<x-guest-layout>

    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4 py-12">

        {{-- Brand / Header --}}
        <div class="flex flex-col items-center text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-blue-600 shadow-lg shadow-blue-600/30 flex items-center justify-center mb-5">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8 text-white"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
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

            <h1 class="text-2xl font-bold text-slate-900">
                TaxCalc <span class="text-blue-600">Unifikasi</span>
            </h1>

            <p class="mt-2 text-sm text-slate-500 max-w-sm">
                Sistem Pemotongan &amp; Rekapitulasi e-Bupot PPh Unifikasi (PER-24/PJ/2021)
            </p>
        </div>

        {{-- Login Card --}}
        <div class="w-full max-w-md bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

            <form
                id="loginForm"
                method="POST"
                action="/login"
                class="space-y-5"
            >
                @csrf

                {{-- Username --}}
                <div>
                    <label
                        for="username"
                        class="block text-xs font-semibold tracking-wide text-slate-600 mb-2"
                    >
                        USERNAME / PETUGAS PAJAK
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="12" cy="8" r="3.5" />
                                <path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6" />
                            </svg>
                        </span>

                        <input
                            id="username"
                            name="username"
                            type="text"
                            value="{{ old('username') }}"
                            placeholder="tax.officer"
                            autocomplete="username"
                            required
                            autofocus
                            class="w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition"
                        />
                    </div>

                    @error('username')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="block text-xs font-semibold tracking-wide text-slate-600 mb-2"
                    >
                        KATA SANDI
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect x="5" y="10.5" width="14" height="9" rx="2" />
                                <path d="M8 10.5V7.5a4 4 0 0 1 8 0v3" />
                            </svg>
                        </span>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition"
                        />
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember + Forgot --}}
                <div class="flex items-center justify-between pt-1">
                    <label
                        for="remember"
                        class="flex items-center gap-2 cursor-pointer select-none"
                    >
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            value="1"
                            checked
                            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500/40"
                        />

                        <span class="text-sm text-slate-600">
                            Ingat saya
                        </span>
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 hover:underline"
                        >
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 active:bg-blue-800 transition focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:ring-offset-2"
                >
                    Masuk ke Aplikasi
                </button>

            </form>
        </div>

    </div>

</x-guest-layout>
