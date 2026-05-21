@extends('layouts.app')

@section('title', 'Manajemen Pasar')
@section('page-title', 'Manajemen Pasar')

@php
$kancabBadge = fn($k) => match(true) {
str_contains($k,'BOGOR') => 'bg-emerald-100 text-emerald-700',
str_contains($k,'CIANJUR') => 'bg-blue-100 text-blue-700',
str_contains($k,'BANDUNG') => 'bg-purple-100 text-purple-700',
str_contains($k,'CIAMIS') => 'bg-amber-100 text-amber-700',
str_contains($k,'CIREBON') => 'bg-orange-100 text-orange-700',
str_contains($k,'INDRAMAYU') => 'bg-teal-100 text-teal-700',
str_contains($k,'SUBANG') => 'bg-indigo-100 text-indigo-700',
str_contains($k,'KARAWANG') => 'bg-red-100 text-red-700',
default => 'bg-gray-100 text-gray-600',
};
@endphp

@section('content')

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
    ['label'=>'Total Pasar', 'value'=>number_format($totalPasar), 'icon'=>'🏪','color'=>'#1d4ed8'],
    ['label'=>'Kantor Cabang', 'value'=>$totalKancab, 'icon'=>'🏢','color'=>'#0369a1'],
    ['label'=>'Total Target', 'value'=>number_format($totalTarget,0,',','.'), 'icon'=>'🎯','color'=>'#0f766e'],
    ['label'=>'Hasil Filter', 'value'=>$pasars->total(), 'icon'=>'🔍','color'=>'#7c3aed'],
    ] as $s)
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-blue-100 flex items-center gap-3">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-2xl shrink-0"
            style="background:{{ $s['color'] }}18">{{ $s['icon'] }}</div>
        <div>
            <p class="text-xs text-blue-400 font-semibold">{{ $s['label'] }}</p>
            <p class="text-xl font-extrabold" style="color:{{ $s['color'] }}">{{ $s['value'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Toolbar --}}
<div class="bg-white rounded-2xl shadow-sm border border-blue-100 px-5 py-4 mb-5 flex flex-wrap items-center gap-3">
    <form method="GET" action="{{ route('pasars.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-48">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-400 text-sm select-none">🔍</span>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama pasar, kabupaten…"
                class="w-full pl-9 pr-4 py-2 rounded-xl border border-blue-200 text-sm bg-blue-50
                          focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
        </div>
        <select name="kantor_cabang"
            class="py-2 px-3 rounded-xl border border-blue-200 text-sm bg-blue-50 text-blue-800
                       focus:outline-none focus:ring-2 focus:ring-blue-400 transition min-w-48">
            <option value="">Semua Kantor Cabang</option>
            @foreach($kancabList as $k)
            <option value="{{ $k }}" {{ request('kantor_cabang')===$k ? 'selected':'' }}>{{ $k }}</option>
            @endforeach
        </select>
        <button type="submit"
            class="px-4 py-2 rounded-xl text-sm font-semibold text-white transition hover:shadow-md hover:-translate-y-0.5"
            style="background:linear-gradient(135deg,#1e40af,#2563eb)">Filter</button>
        @if(request('search') || request('kantor_cabang'))
        <a href="{{ route('pasars.index') }}"
            class="px-4 py-2 rounded-xl text-sm font-semibold text-blue-500 border border-blue-200 hover:bg-blue-50 transition">
            Reset
        </a>
        @endif
    </form>
   @if(Auth::user()->role === 'admin')
    <a href="{{ route('pasars.create') }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white shadow-md
              hover:shadow-lg hover:-translate-y-0.5 transition whitespace-nowrap"
        style="background:linear-gradient(135deg,#1e40af,#2563eb)">
        <span class="text-base leading-none">+</span> Tambah Pasar
    </a>
    @endif
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                    @foreach(['#','Nama Pasar','Kantor Cabang','Kabupaten / Kota','Target','Aksi'] as $h)
                    <th class="text-left px-4 py-3.5 text-white font-semibold text-xs uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse($pasars as $i => $pasar)
                <tr class="tbl-row transition-colors group">
                    <td class="px-4 py-3.5 text-blue-400 font-mono text-xs font-bold">
                        {{ $pasars->firstItem() + $i }}
                    </td>
                    <td class="px-4 py-3.5">
                        <p class="font-semibold text-blue-900">{{ $pasar->nama_pasar }}</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $kancabBadge($pasar->kantor_cabang) }}">
                            {{ $pasar->kantor_cabang }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $pasar->kabupaten }}</td>
                    <td class="px-4 py-3.5">
                        <span class="inline-flex items-center gap-1 font-bold text-blue-800">
                            {{ number_format($pasar->target,0,',','.') }} Kg
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1.5 opacity-60 group-hover:opacity-100 transition">

                            {{-- Detail --}}
                            <a href="{{ route('pasars.show', $pasar) }}" title="Detail"
                                class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-500 hover:text-blue-700
                  flex items-center justify-center transition hover:scale-110 border border-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                            @if(Auth::user()->role  === 'admin')
                            {{-- Edit --}}
                            <a href="{{ route('pasars.edit', $pasar) }}" title="Edit"
                                class="w-8 h-8 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-500 hover:text-amber-700
                  flex items-center justify-center transition hover:scale-110 border border-amber-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <button type="button" title="Hapus"
                                onclick="confirmDelete({{ $pasar->id }},'{{ addslashes($pasar->nama_pasar) }}')"
                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600
                       flex items-center justify-center transition hover:scale-110 border border-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>

                            <form id="del-{{ $pasar->id }}" action="{{ route('pasars.destroy',$pasar) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16 text-blue-300">
                        <p class="text-4xl mb-3">🏪</p>
                        <p class="font-semibold">Tidak ada data pasar</p>
                        <a href="{{ route('pasars.create') }}" class="text-xs text-blue-500 underline mt-1 inline-block">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between px-5 py-3.5 border-t border-blue-50 bg-blue-50/60">
        <p class="text-xs text-blue-400 font-medium">
            Menampilkan {{ $pasars->firstItem() ?? 0 }}–{{ $pasars->lastItem() ?? 0 }} dari {{ $pasars->total() }} data
        </p>
        <div class="flex items-center gap-1">
            @if($pasars->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-300 border border-blue-100 cursor-not-allowed">← Prev</span>
            @else
            <a href="{{ $pasars->previousPageUrl() }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-white transition">← Prev</a>
            @endif

            @foreach($pasars->getUrlRange(max(1,$pasars->currentPage()-2), min($pasars->lastPage(),$pasars->currentPage()+2)) as $p => $url)
            @if($p === $pasars->currentPage())
            <span class="w-8 h-8 rounded-lg text-xs font-bold text-white flex items-center justify-center"
                style="background:linear-gradient(135deg,#1e40af,#2563eb)">{{ $p }}</span>
            @else
            <a href="{{ $url }}"
                class="w-8 h-8 rounded-lg text-xs font-semibold text-blue-500 border border-blue-200 hover:bg-white flex items-center justify-center transition">{{ $p }}</a>
            @endif
            @endforeach

            @if($pasars->hasMorePages())
            <a href="{{ $pasars->nextPageUrl() }}"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-600 border border-blue-200 hover:bg-white transition">Next →</a>
            @else
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-300 border border-blue-100 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="del-modal" class="fixed inset-0 z-50 hidden items-center justify-center"
    style="background:rgba(15,30,60,0.55);backdrop-filter:blur(2px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4">
        <div class="px-6 py-6 text-center">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4 text-3xl">🗑️</div>
            <p class="text-gray-600 mb-1 text-sm">Yakin ingin menghapus pasar ini?</p>
            <p id="del-name" class="font-bold text-blue-800 mb-5 text-sm"></p>
            <div class="flex gap-3">
                <button id="del-confirm"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition">
                    Ya, Hapus
                </button>
                <button onclick="closeDelModal()"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let pendingId = null;

    function confirmDelete(id, name) {
        pendingId = id;
        document.getElementById('del-name').textContent = '"' + name + '"';
        const m = document.getElementById('del-modal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closeDelModal() {
        pendingId = null;
        const m = document.getElementById('del-modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    document.getElementById('del-confirm').addEventListener('click', () => {
        if (pendingId) document.getElementById('del-' + pendingId).submit();
    });
    document.getElementById('del-modal').addEventListener('click', function(e) {
        if (e.target === this) closeDelModal();
    });
</script>
@endpush