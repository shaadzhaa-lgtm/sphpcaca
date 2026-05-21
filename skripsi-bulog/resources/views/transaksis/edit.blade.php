@extends('layouts.app')

@section('title', 'Edit Transaksi')
@section('page-title', 'Edit Transaksi')
@section('breadcrumb')
    <a href="{{ route('transaksis.index') }}" class="hover:text-blue-600 transition">Transaksi</a>
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    <span class="text-blue-600 font-semibold">Edit #{{ $transaksi->id }}</span>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-blue-100 flex items-center gap-3"
             style="background:linear-gradient(135deg,#fffbeb,#fef3c7)">
            <div class="w-9 h-9 rounded-xl bg-amber-400 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
            </div>
            <div>
                <h2 class="font-bold text-blue-900 text-base">Edit Transaksi #{{ $transaksi->id }}</h2>
                <p class="text-blue-400 text-xs">{{ $transaksi->pasar->nama_pasar ?? '' }} — {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="px-6 py-6">
            @include('transaksis._form')
        </div>
    </div>
</div>
@endsection