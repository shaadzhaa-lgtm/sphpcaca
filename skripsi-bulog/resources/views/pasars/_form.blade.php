@php
    $isEdit   = isset($pasar);
    $old      = fn($key, $fallback = '') => old($key, $isEdit ? $pasar->{$key} : $fallback);
    $input    = 'w-full rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm text-gray-800
                 placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition';
    $label    = 'block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5';
    $errClass = 'mt-1 text-xs text-red-500 font-medium';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('pasars.update', $pasar) : route('pasars.store') }}"
      class="space-y-5">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        {{-- Kantor Cabang --}}
        <div>
            <label class="{{ $label }}">Kantor Cabang <span class="text-red-400">*</span></label>
            <select name="kantor_cabang"
                    class="{{ $input }} @error('kantor_cabang') border-red-400 @enderror">
                <option value="">-- Pilih --</option>
                @foreach($kancabList as $k)
                    <option value="{{ $k }}" {{ $old('kantor_cabang') === $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
            @error('kantor_cabang') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
        </div>

        {{-- Kabupaten --}}
        <div>
            <label class="{{ $label }}">Kabupaten / Kota <span class="text-red-400">*</span></label>
            <input type="text" name="kabupaten" value="{{ $old('kabupaten') }}"
                   placeholder="Kab. Bogor / Kota Bandung"
                   class="{{ $input }} @error('kabupaten') border-red-400 @enderror">
            @error('kabupaten') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Nama Pasar --}}
    <div>
        <label class="{{ $label }}">Nama Pasar <span class="text-red-400">*</span></label>
        <input type="text" name="nama_pasar" value="{{ $old('nama_pasar') }}"
               placeholder="PASAR CIMAYANG"
               class="{{ $input }} @error('nama_pasar') border-red-400 @enderror">
        @error('nama_pasar') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        {{-- Latitude --}}
        <div>
            <label class="{{ $label }}">Latitude <span class="text-red-400">*</span></label>
            <input type="number" name="latitude" value="{{ $old('latitude') }}"
                   step="0.0000001" placeholder="-6.607979"
                   class="{{ $input }} font-mono @error('latitude') border-red-400 @enderror">
            @error('latitude') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
        </div>

        {{-- Longitude --}}
        <div>
            <label class="{{ $label }}">Longitude <span class="text-red-400">*</span></label>
            <input type="number" name="longitude" value="{{ $old('longitude') }}"
                   step="0.0000001" placeholder="106.668038"
                   class="{{ $input }} font-mono @error('longitude') border-red-400 @enderror">
            @error('longitude') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Target --}}
    <div>
        <label class="{{ $label }}">Target <span class="text-red-400">*</span></label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-blue-400 text-sm select-none">🎯</span>
            <input type="number" name="target" value="{{ $old('target', 0) }}"
                   min="0" placeholder="0"
                   class="pl-9 {{ $input }} @error('target') border-red-400 @enderror">
        </div>
        @error('target') <p class="{{ $errClass }}">{{ $message }}</p> @enderror
    </div>

    {{-- Actions --}}
    <div class="flex items-center gap-3 pt-1">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white shadow-md
                       hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition"
                style="background:linear-gradient(135deg,#1e40af,#2563eb)">
            {{ $isEdit ? '💾 Simpan Perubahan' : '➕ Tambah Pasar' }}
        </button>
        <a href="{{ route('pasars.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition">
            Batal
        </a>
    </div>
</form>