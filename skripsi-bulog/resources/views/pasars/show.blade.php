@extends('layouts.app')

@section('title', $pasar->nama_pasar)
@section('page-title', 'Detail Pasar')
@section('breadcrumb') <span class="text-blue-600 font-semibold">Detail</span> @endsection

@php
$badge = match(true) {
    str_contains($pasar->kantor_cabang,'BOGOR')     => 'bg-emerald-100 text-emerald-700',
    str_contains($pasar->kantor_cabang,'CIANJUR')   => 'bg-blue-100 text-blue-700',
    str_contains($pasar->kantor_cabang,'BANDUNG')   => 'bg-purple-100 text-purple-700',
    str_contains($pasar->kantor_cabang,'CIAMIS')    => 'bg-amber-100 text-amber-700',
    str_contains($pasar->kantor_cabang,'CIREBON')   => 'bg-orange-100 text-orange-700',
    str_contains($pasar->kantor_cabang,'INDRAMAYU') => 'bg-teal-100 text-teal-700',
    str_contains($pasar->kantor_cabang,'SUBANG')    => 'bg-indigo-100 text-indigo-700',
    str_contains($pasar->kantor_cabang,'KARAWANG')  => 'bg-red-100 text-red-700',
    default                                         => 'bg-gray-100 text-gray-600',
};
@endphp

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        {{-- Hero --}}
        <div class="px-6 py-6 flex items-center gap-4"
             style="background:linear-gradient(135deg,#1e3a8a,#2563eb)">
            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl shrink-0">🏪</div>
            <div class="flex-1 min-w-0">
                <h2 class="text-white font-extrabold text-lg leading-tight">{{ $pasar->nama_pasar }}</h2>
                <span class="inline-block mt-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                    {{ $pasar->kantor_cabang }}
                </span>
            </div>
        </div>

        {{-- Detail rows --}}
        <div class="divide-y divide-blue-50">
            @foreach([
                ['🏢', 'Kantor Cabang',    $pasar->kantor_cabang,                           false],
                ['📍', 'Kabupaten / Kota', $pasar->kabupaten,                               false],
                ['🗺',  'Latitude',         number_format($pasar->latitude,  7, '.', ''),    true],
                ['🗺',  'Longitude',        number_format($pasar->longitude, 7, '.', ''),    true],
                ['🎯', 'Target',            number_format($pasar->target, 0, ',', '.'),      false],
                ['📅', 'Dibuat',            $pasar->created_at?->format('d M Y, H:i') ?? '—', false],
                ['🔄', 'Diperbarui',        $pasar->updated_at?->format('d M Y, H:i') ?? '—', false],
            ] as $idx => [$icon, $lbl, $val, $mono])
            <div class="flex items-center gap-4 px-6 py-3.5 {{ $idx % 2 === 0 ? 'bg-blue-50/40' : 'bg-white' }}">
                <span class="text-lg shrink-0">{{ $icon }}</span>
                <span class="w-36 shrink-0 text-xs font-semibold text-blue-400 uppercase tracking-wider">{{ $lbl }}</span>
                <span class="text-sm font-medium text-gray-800 {{ $mono ? 'font-mono' : '' }}">{{ $val }}</span>
            </div>
            @endforeach
        </div>

        {{-- Maps link --}}
        <div class="px-6 py-3.5 border-t border-blue-50 bg-blue-50/50">
            <a href="https://www.google.com/maps?q={{ $pasar->latitude }},{{ $pasar->longitude }}"
               target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 text-sm text-blue-600 font-semibold hover:text-blue-800 transition">
                🗺 Lihat di Google Maps
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 px-6 py-4 border-t border-blue-100">
            <a href="{{ route('pasars.edit', $pasar) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow-md
                      hover:shadow-lg hover:-translate-y-0.5 transition"
               style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                ✏️ Edit Data
            </a>
            <a href="{{ route('pasars.index') }}"
               class="px-5 py-2.5 rounded-xl text-sm font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition">
                ← Kembali
            </a>
            <form action="{{ route('pasars.destroy', $pasar) }}" method="POST" class="ml-auto"
                  onsubmit="return confirm('Yakin ingin menghapus pasar ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-red-500 border border-red-200 hover:bg-red-50 transition">
                    🗑 Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection