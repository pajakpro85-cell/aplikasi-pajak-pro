<x-app-layout>

    <div class="h-screen w-full flex overflow-hidden bg-slate-50">

        <x-sidebar active="asisten-pajak-ai" />

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

            {{-- ---- MAIN CONTENT ---- --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-6">

                <div class="bg-white rounded-2xl border border-slate-200 min-h-[calc(100vh-8rem)] flex flex-col items-center justify-center text-center px-6">

                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-5" style="background-color:#FEF3C7">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" style="color:#D97706" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                            <path d="M12 9v4"/>
                            <path d="M12 17h.01"/>
                        </svg>
                    </div>

                    <h1 class="text-2xl font-bold text-slate-900 mb-3">Assistant Unavailable</h1>

                    <p class="text-slate-500 leading-relaxed max-w-lg">
                        The AI Tax Assistant requires a valid Google Gemini API Key.<br>
                        Please configure <code class="font-mono text-slate-600">process.env.API_KEY</code> in your environment.
                    </p>

                </div>

            </main>
        </div>
    </div>

</x-app-layout>