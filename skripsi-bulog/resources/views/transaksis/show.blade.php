@extends('layouts.app')

@section('title', 'Detail Transaksi #' . $transaksi->id)
@section('page-title', 'Detail Transaksi')
@section('breadcrumb')
    <a href="{{ route('transaksis.index') }}" class="hover:text-blue-600 transition">Transaksi</a>
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    <span class="text-blue-600 font-semibold">#{{ $transaksi->id }}</span>
@endsection

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        {{-- Hero --}}
        <div class="px-6 py-5 flex items-center gap-4"
             style="background:linear-gradient(135deg,#1e3a8a,#2563eb)">
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"/>
                </svg>
            </div>
            <div>
                <p class="text-blue-200 text-xs font-medium">Transaksi #{{ $transaksi->id }}</p>
                <h2 class="text-white font-extrabold text-base">{{ $transaksi->pasar->nama_pasar ?? '—' }}</h2>
                <p class="text-blue-200 text-xs mt-0.5">{{ $transaksi->pasar->kantor_cabang ?? '' }}</p>
            </div>
        </div>

        {{-- Omzet highlight --}}
        <div class="px-6 py-4 border-b border-blue-50 flex items-center justify-between"
             style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
            <div>
                <p class="text-xs text-blue-500 font-semibold uppercase tracking-wider">Total Omzet</p>
                <p class="text-2xl font-extrabold text-blue-800 mt-0.5">
                    Rp {{ number_format($transaksi->jumlah_kg * $transaksi->harga_jual, 0, ',', '.') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-xs text-blue-400">{{ number_format($transaksi->jumlah_kg, 1) }} kg
                    × Rp {{ number_format($transaksi->harga_jual, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Detail rows --}}
        <div class="divide-y divide-blue-50">
            @foreach([
                ['icon' => 'M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016 2.993 2.993 0 0 0 2.25-1.016 3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z',
                  'label' => 'Pasar', 'value' => $transaksi->pasar->nama_pasar ?? '—', 'mono' => false],
                ['icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5',
                  'label' => 'Tanggal', 'value' => \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y'), 'mono' => false],
                ['icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
                  'label' => 'Jumlah (kg)', 'value' => number_format($transaksi->jumlah_kg, 1) . ' kg', 'mono' => true],
                ['icon' => 'M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z',
                  'label' => 'Harga Jual', 'value' => 'Rp ' . number_format($transaksi->harga_jual, 0, ',', '.') . ' / kg', 'mono' => true],
                ['icon' => 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z',
                  'label' => 'Keterangan', 'value' => $transaksi->keterangan ?? '—', 'mono' => false],
                ['icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
                  'label' => 'Dibuat', 'value' => $transaksi->created_at->format('d M Y, H:i'), 'mono' => false],
            ] as $idx => $row)
            <div class="flex items-center gap-4 px-6 py-3.5 {{ $idx % 2 === 0 ? 'bg-blue-50/40' : 'bg-white' }}">
                <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $row['icon'] }}"/>
                    </svg>
                </div>
                <span class="w-28 shrink-0 text-xs font-semibold text-blue-400 uppercase tracking-wider">{{ $row['label'] }}</span>
                <span class="text-sm font-medium text-gray-800 {{ $row['mono'] ? 'font-mono' : '' }}">{{ $row['value'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 px-6 py-4 border-t border-blue-100">
            <a href="{{ route('transaksis.edit', $transaksi) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition"
               style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                Edit
            </a>
            <a href="{{ route('transaksis.index') }}"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition">
                ← Kembali
            </a>
            <form action="{{ route('transaksis.destroy', $transaksi) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Hapus transaksi ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-red-500 border border-red-200 hover:bg-red-50 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection