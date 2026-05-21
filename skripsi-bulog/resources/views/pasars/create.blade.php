@extends('layouts.app')

@section('title', 'Tambah Pasar')
@section('page-title', 'Tambah Pasar Baru')
@section('breadcrumb') <span class="text-blue-600 font-semibold">Tambah</span> @endsection

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-blue-100 flex items-center gap-3"
             style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                 style="background:linear-gradient(135deg,#1e40af,#2563eb)">+</div>
            <div>
                <h2 class="font-bold text-blue-900 text-base">Tambah Pasar Baru</h2>
                <p class="text-blue-400 text-xs">Isi seluruh kolom bertanda (*)</p>
            </div>
        </div>

        <div class="px-6 py-6">
            @include('pasars._form')
        </div>
    </div>
</div>
@endsection