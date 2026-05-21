@extends('layouts.app')

@section('title', 'Transaksi')
@section('page-title', 'Performance Transaksi')
@section('breadcrumb')<span class="text-blue-600 font-semibold">Transaksi</span>@endsection

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- ── Filter bar ────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('transaksis.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-blue-100 px-5 py-4 mb-6
             flex flex-wrap items-end gap-3">

    <div class="flex-1 min-w-36">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
        <input type="date" name="date_from" value="{{ $dateFrom }}"
               class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
    </div>
    <div class="flex-1 min-w-36">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
        <input type="date" name="date_to" value="{{ $dateTo }}"
               class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
    </div>
    <div class="flex-1 min-w-44">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Kantor Cabang</label>
        <select name="kantor_cabang"
                class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            <option value="">Semua Kantor Cabang</option>
            @foreach($kancabList as $k)
                <option value="{{ $k }}" {{ $kancab === $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1 min-w-52">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Pasar</label>
        <select name="pasar_id"
                class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            <option value="">Semua Pasar</option>
            @foreach($pasarList as $p)
                <option value="{{ $p->id }}" {{ $pasarId == $p->id ? 'selected' : '' }}>
                    {{ $p->nama_pasar }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-2 items-end">
        <button type="submit"
                class="px-5 py-2 rounded-xl text-sm font-bold text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition"
                style="background:linear-gradient(135deg,#1e40af,#2563eb)">
            Terapkan
        </button>
        <a href="{{ route('transaksis.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold text-blue-500 border border-blue-200 hover:bg-blue-50 transition">
            Reset
        </a>
        @if(Auth::user()->role === 'admin')
        <a href="{{ route('transaksis.create') }}"
           class="px-4 py-2 rounded-xl text-sm font-bold text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition
                  flex items-center gap-1.5"
           style="background:linear-gradient(135deg,#0f766e,#0d9488)">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Tambah
        </a>
        @endif
    </div>
</form>

{{-- ── KPI Cards ─────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards = [
            [
                'label' => 'Total Transaksi',
                'value' => number_format($kpi->total_transaksi ?? 0),
                'sub'   => 'data tercatat',
                'color' => '#1d4ed8',
                'd'     => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
            ],
            [
                'label' => 'Total Volume',
                'value' => number_format($kpi->total_kg ?? 0, 1, ',', '.') . ' kg',
                'sub'   => 'jumlah terjual',
                'color' => '#0369a1',
                'd'     => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
            ],
            [
                'label' => 'Rata-rata / Tx',
                'value' => number_format($kpi->avg_kg ?? 0, 2, ',', '.') . ' kg',
                'sub'   => 'per transaksi',
                'color' => '#0f766e',
                'd'     => 'M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z',
            ],
            [
                'label' => 'Pasar Aktif',
                'value' => number_format($kpi->active_pasar ?? 0),
                'sub'   => 'dari ' . \App\Models\Pasar::count() . ' terdaftar',
                'color' => '#7c3aed',
                'd'     => 'M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016 2.993 2.993 0 0 0 2.25-1.016 3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z',
            ],
        ];
    @endphp

    @foreach($cards as $c)
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-blue-100">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs text-blue-400 font-semibold">{{ $c['label'] }}</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                 style="background:{{ $c['color'] }}18">
                <svg class="w-4 h-4" style="color:{{ $c['color'] }}" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['d'] }}"/>
                </svg>
            </div>
        </div>
        <p class="text-lg font-extrabold leading-tight" style="color:{{ $c['color'] }}">{{ $c['value'] }}</p>
        <p class="text-[10px] text-blue-300 mt-0.5">{{ $c['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Performance per Kantor Cabang ────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden mb-6">

    <div class="px-5 py-4 border-b border-blue-50 flex items-center justify-between"
         style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
        <div>
            <h3 class="font-bold text-blue-900 text-sm">Performance per Kantor Cabang</h3>
            <p class="text-[11px] text-blue-400 mt-0.5">
                {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} —
                {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}
            </p>
        </div>
        <span class="text-xs text-blue-500 font-semibold bg-white px-3 py-1 rounded-xl border border-blue-100">
            {{ $kancabPerf->count() }} kancab
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-blue-100 bg-blue-50/60">
                    <th class="text-left px-5 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Kantor Cabang</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Total Transaksi</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Total Volume (kg)</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Avg / Tx (kg)</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Avg Harga</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Pasar Aktif</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-blue-500 uppercase tracking-wider">Kontribusi Volume</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @php $maxKg = $kancabPerf->max('total_kg') ?: 1; @endphp
                @forelse($kancabPerf as $i => $perf)
                <tr class="tbl-row transition-colors">
                    {{-- Nama kancab + rank --}}
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg text-[10px] font-extrabold flex items-center justify-center shrink-0
                                {{ $i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-slate-300 text-slate-700' : ($i === 2 ? 'bg-orange-300 text-white' : 'bg-blue-100 text-blue-500')) }}">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <p class="font-semibold text-blue-900 text-xs">{{ $perf->kantor_cabang }}</p>
                                <p class="text-[10px] text-blue-300">{{ $perf->total_pasar }} pasar terdaftar</p>
                            </div>
                        </div>
                    </td>

                    {{-- Total transaksi --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-mono text-xs font-semibold text-gray-700">
                            {{ number_format($perf->total_transaksi) }}
                        </span>
                    </td>

                    {{-- Total kg --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-mono text-xs font-bold text-blue-700">
                            {{ number_format($perf->total_kg, 1, ',', '.') }}
                        </span>
                    </td>

                    {{-- Avg per tx --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-mono text-xs text-gray-600">
                            {{ number_format($perf->avg_kg, 2, ',', '.') }}
                        </span>
                    </td>

                    {{-- Avg harga --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-mono text-xs text-gray-600">
                            Rp {{ number_format($perf->avg_harga, 0, ',', '.') }}
                        </span>
                    </td>

                    {{-- Pasar aktif + pct --}}
                    <td class="px-4 py-3.5 text-right">
                        <span class="font-mono text-xs font-semibold text-gray-700">
                            {{ $perf->pasar_aktif }}
                        </span>
                        <span class="text-[10px] text-blue-400 ml-1">
                            ({{ $perf->pct_pasar_aktif }}%)
                        </span>
                    </td>


                    {{-- Kontribusi volume bar --}}
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-blue-50 rounded-full h-2">
                                <div class="h-2 rounded-full" style="width:{{ $perf->kontribusi_pct }}%;background:linear-gradient(90deg,#1e40af,#2563eb)"></div>
                            </div>
                            <span class="text-[10px] font-bold text-blue-600 w-10 text-right shrink-0">
                                {{ $perf->kontribusi_pct }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-blue-300 text-sm">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Charts row ────────────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Multi-line: volume harian per kancab (top 5) --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-blue-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-blue-900 text-sm">Tren Volume Harian per Kancab</h3>
                <p class="text-[11px] text-blue-400">Top 5 Kantor Cabang</p>
            </div>
        </div>
        <div class="relative h-52">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    {{-- Bar: volume per kancab --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5">
        <div class="mb-4">
            <h3 class="font-bold text-blue-900 text-sm">Volume per Kancab</h3>
            <p class="text-[11px] text-blue-400">Total kg periode ini</p>
        </div>
        <div class="relative h-52">
            <canvas id="barKancab"></canvas>
        </div>
    </div>
</div>

{{-- ── Top 10 Pasar + Daily total ───────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    {{-- Top 10 pasar by volume --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5">
        <h3 class="font-bold text-blue-900 text-sm mb-4">Top 10 Pasar — Volume Tertinggi</h3>
        @php $maxKgPasar = $topPasars->max('total_kg') ?: 1; @endphp
        <div class="space-y-2.5">
            @forelse($topPasars as $i => $tp)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-5 h-5 rounded-md text-[10px] font-extrabold flex items-center justify-center shrink-0
                            {{ $i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-slate-300 text-slate-700' : ($i === 2 ? 'bg-orange-300 text-white' : 'bg-blue-100 text-blue-500')) }}">
                            {{ $i + 1 }}
                        </span>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-gray-700 truncate" title="{{ $tp->nama_pasar }}">
                                {{ $tp->nama_pasar }}
                            </p>
                            <p class="text-[10px] text-blue-300">{{ str_replace('KANCAB ', '', $tp->kantor_cabang) }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-blue-700 shrink-0 ml-2 font-mono">
                        {{ number_format($tp->total_kg, 1) }} kg
                    </span>
                </div>
                <div class="w-full bg-blue-50 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="width:{{ round($tp->total_kg / $maxKgPasar * 100) }}%;background:linear-gradient(90deg,#1e40af,#2563eb)"></div>
                </div>
            </div>
            @empty
            <p class="text-sm text-blue-300 text-center py-6">Tidak ada data</p>
            @endforelse
        </div>
    </div>

    {{-- Volume harian total --}}
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5">
        <div class="mb-4">
            <h3 class="font-bold text-blue-900 text-sm">Volume Harian (Total)</h3>
            <p class="text-[11px] text-blue-400">Agregat semua kancab</p>
        </div>
        <div class="relative h-52">
            <canvas id="dailyTotalChart"></canvas>
        </div>
    </div>
</div>

{{-- ── Transaksi Table ───────────────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-blue-50 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-blue-900 text-sm">Data Transaksi</h3>
            <p class="text-[11px] text-blue-400 mt-0.5">{{ number_format($transaksis->total()) }} transaksi ditemukan</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                    @foreach(['#','Pasar','Kancab','Tanggal','Jumlah (kg)','Harga Jual','Keterangan','Aksi'] as $h)
                    <th class="text-left px-4 py-3 text-white font-semibold text-xs uppercase tracking-wider whitespace-nowrap">
                        {{ $h }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($transaksis as $i => $tx)
                <tr class="tbl-row transition-colors group">
                    <td class="px-4 py-3 text-blue-300 font-mono text-xs">{{ $transaksis->firstItem() + $i }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-blue-900 text-xs">{{ $tx->pasar->nama_pasar ?? '—' }}</p>
                        <p class="text-[10px] text-blue-300">{{ $tx->pasar->kabupaten ?? '' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-700 whitespace-nowrap">
                            {{ str_replace('KANCAB ', '', $tx->pasar->kantor_cabang ?? '—') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500 font-mono whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($tx->tanggal)->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3 font-mono text-xs font-bold text-blue-700">
                        {{ number_format($tx->jumlah_kg, 1) }} kg
                    </td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">
                        Rp {{ number_format($tx->harga_jual, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400 max-w-28 truncate">
                        {{ $tx->keterangan ?? '—' }}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition">
                            <a href="{{ route('transaksis.show', $tx) }}" title="Detail"
                               class="w-7 h-7 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-500 hover:text-blue-700
                                      flex items-center justify-center transition hover:scale-110 border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                            </a>
                            <a href="{{ route('transaksis.edit', $tx) }}" title="Edit"
                               class="w-7 h-7 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-500 hover:text-amber-700
                                      flex items-center justify-center transition hover:scale-110 border border-amber-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                </svg>
                            </a>
                            <button type="button" title="Hapus"
                                    onclick="confirmDelete({{ $tx->id }})"
                                    class="w-7 h-7 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600
                                           flex items-center justify-center transition hover:scale-110 border border-red-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                            <form id="del-tx-{{ $tx->id }}" action="{{ route('transaksis.destroy', $tx) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-14 text-blue-300">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                        <p class="font-semibold text-sm">Tidak ada data transaksi</p>
                        <p class="text-xs mt-1">Coba ubah filter tanggal atau pasar</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between px-5 py-3.5 border-t border-blue-50 bg-blue-50/50">
        <p class="text-xs text-blue-400 font-medium">
            Menampilkan {{ $transaksis->firstItem() ?? 0 }}–{{ $transaksis->lastItem() ?? 0 }}
            dari {{ number_format($transaksis->total()) }} data
        </p>
        <div class="flex items-center gap-1">
            @if($transaksis->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-300 border border-blue-100 cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $transaksis->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-white transition">← Prev</a>
            @endif
            @foreach($transaksis->getUrlRange(max(1,$transaksis->currentPage()-2), min($transaksis->lastPage(),$transaksis->currentPage()+2)) as $p => $url)
                @if($p === $transaksis->currentPage())
                    <span class="w-8 h-8 rounded-lg text-xs font-bold text-white flex items-center justify-center"
                          style="background:linear-gradient(135deg,#1e40af,#2563eb)">{{ $p }}</span>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg text-xs font-semibold text-blue-500 border border-blue-200 hover:bg-white flex items-center justify-center transition">{{ $p }}</a>
                @endif
            @endforeach
            @if($transaksis->hasMorePages())
                <a href="{{ $transaksis->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-white transition">Next →</a>
            @else
                <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-300 border border-blue-100 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="del-modal" class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(15,30,60,0.55);backdrop-filter:blur(2px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 text-center">
        <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-6 h-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
            </svg>
        </div>
        <p class="text-gray-600 text-sm mb-1">Yakin hapus transaksi ini?</p>
        <p class="text-blue-300 text-xs mb-5">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex gap-3">
            <button id="del-confirm" class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition">Hapus</button>
            <button onclick="closeDelModal()" class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 transition">Batal</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Delete modal ──────────────────────────────────────────────────────────────
let pendingDelId = null;
function confirmDelete(id) {
    pendingDelId = id;
    const m = document.getElementById('del-modal');
    m.classList.remove('hidden'); m.classList.add('flex');
}
function closeDelModal() {
    pendingDelId = null;
    const m = document.getElementById('del-modal');
    m.classList.add('hidden'); m.classList.remove('flex');
}
document.getElementById('del-confirm').addEventListener('click', () => {
    if (pendingDelId) document.getElementById('del-tx-' + pendingDelId).submit();
});
document.getElementById('del-modal').addEventListener('click', e => {
    if (e.target === e.currentTarget) closeDelModal();
});

// ── Chart data ────────────────────────────────────────────────────────────────
const dailyKancab  = @json($dailyKancab);
const kancabPerf   = @json($kancabPerf);
const dailyTotal   = @json($dailyTotal);

// Warna per kancab (konsisten)
const COLORS = [
    '#1e40af','#0369a1','#0f766e','#7c3aed',
    '#b45309','#be123c','#15803d','#6d28d9'
];

// ── Multi-line: volume harian per top 5 kancab ────────────────────────────────
const allDates = [...new Set(
    Object.values(dailyKancab).flat().map(r => r.tanggal)
)].sort();

const lineDatasets = Object.entries(dailyKancab).map(([kancab, rows], i) => {
    const byDate = {};
    rows.forEach(r => byDate[r.tanggal] = r.total_kg);
    return {
        label: kancab.replace('KANCAB ', ''),
        data: allDates.map(d => byDate[d] || 0),
        borderColor: COLORS[i] || '#94a3b8',
        backgroundColor: (COLORS[i] || '#94a3b8') + '18',
        borderWidth: 2,
        pointRadius: allDates.length > 60 ? 0 : 2,
        pointHoverRadius: 4,
        fill: false,
        tension: 0.35,
    };
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: allDates.map(d => {
            const dt = new Date(d);
            return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        }),
        datasets: lineDatasets,
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 12, padding: 8 } },
            tooltip: { callbacks: { label: ctx => ctx.dataset.label + ': ' + ctx.raw.toLocaleString('id-ID') + ' kg' } },
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 10 } },
            y: { grid: { color: '#f0f9ff' }, ticks: { font: { size: 10 },
                callback: v => v >= 1000 ? (v/1000).toFixed(1)+'k' : v
            }}
        }
    }
});

// ── Bar: volume per kancab ───────────────────────────────────────────────────
new Chart(document.getElementById('barKancab'), {
    type: 'bar',
    data: {
        labels: kancabPerf.map(k => k.kantor_cabang.replace('KANCAB ', '')),
        datasets: [{
            label: 'Total Volume (kg)',
            data: kancabPerf.map(k => k.total_kg),
            backgroundColor: COLORS,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false, indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ctx.raw.toLocaleString('id-ID') + ' kg' } },
        },
        scales: {
            x: { grid: { color: '#f0f9ff' }, ticks: { font: { size: 10 },
                callback: v => v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
            }},
            y: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});

// ── Line: volume harian total ─────────────────────────────────────────────────
new Chart(document.getElementById('dailyTotalChart'), {
    type: 'line',
    data: {
        labels: dailyTotal.map(d => {
            const dt = new Date(d.tanggal);
            return dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        }),
        datasets: [{
            label: 'Total Volume',
            data: dailyTotal.map(d => d.total_kg),
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            borderWidth: 2,
            pointRadius: dailyTotal.length > 60 ? 0 : 3,
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ctx.raw.toLocaleString('id-ID') + ' kg' } },
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 }, maxTicksLimit: 10 } },
            y: { grid: { color: '#f0f9ff' }, ticks: { font: { size: 10 },
                callback: v => v >= 1000 ? (v/1000).toFixed(1)+'k' : v
            }}
        }
    }
});
</script>
@endpush