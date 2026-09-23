@props(['active' => ''])

@php
    $textClass = fn (string $key) => $active === $key
        ? 'text-blue-600 text-sm font-semibold'
        : 'text-slate-500 hover:text-slate-700 text-sm font-medium transition';
@endphp

<div class="relative flex items-center gap-8 border-b border-slate-200 mb-6 overflow-x-auto"
    x-data="rekapTabs('{{ $active }}')" x-init="init()">

    <a href="{{ url('/rekapitulasi') }}" data-tab-key="objek-pajak" class="px-1 pb-3 whitespace-nowrap {{ $textClass('objek-pajak') }}">Rekap Objek Pajak (Masa)</a>
    <a href="{{ url('/rekapitulasi/klaster-pph') }}" data-tab-key="klaster-pph" class="px-1 pb-3 whitespace-nowrap {{ $textClass('klaster-pph') }}">Rekap Klaster Pph</a>
    <a href="{{ url('/rekapitulasi/lawan-transaksi') }}" data-tab-key="lawan-transaksi" class="px-1 pb-3 whitespace-nowrap {{ $textClass('lawan-transaksi') }}">Rekap Lawan Transaksi / Klien (YTD)</a>

    {{-- Indikator sliding: posisi/lebar diatur oleh JS (rekapTabs) --}}
    <div x-ref="indicator" class="absolute bottom-0 h-0.5 bg-blue-600"></div>
</div>