@extends('layouts.app')

@section('title', 'Edit Pasar')
@section('page-title', 'Edit Data Pasar')
@section('breadcrumb') <span class="text-blue-600 font-semibold">Edit</span> @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-blue-100 flex items-center gap-3"
             style="background:linear-gradient(135deg,#fffbeb,#fef3c7)">
            <div class="w-9 h-9 rounded-xl bg-amber-400 flex items-center justify-center text-white text-lg">✏️</div>
            <div>
                <h2 class="font-bold text-blue-900 text-base">{{ $pasar->nama_pasar }}</h2>
                <p class="text-blue-400 text-xs">Perbarui data pasar — semua kolom (*) wajib diisi</p>
            </div>
        </div>

        <div class="px-6 py-6">
            @include('pasars._form')
        </div>
    </div>
</div>
@endsection