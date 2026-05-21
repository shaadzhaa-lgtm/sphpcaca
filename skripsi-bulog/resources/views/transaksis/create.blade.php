@extends('layouts.app')

@section('title', 'Tambah Transaksi')
@section('page-title', 'Tambah Transaksi')
@section('breadcrumb')
    <a href="{{ route('transaksis.index') }}" class="hover:text-blue-600 transition">Transaksi</a>
    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
    <span class="text-blue-600 font-semibold">Tambah</span>
@endsection

@section('content')
<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-blue-100 flex items-center gap-3"
             style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            </div>
            <div>
                <h2 class="font-bold text-blue-900 text-base">Tambah Transaksi Baru</h2>
                <p class="text-blue-400 text-xs">Isi semua kolom yang bertanda (*)</p>
            </div>
        </div>

        <div class="px-6 py-6">
            @include('transaksis._form')
        </div>
    </div>
</div>
@endsection