@php
    $isEdit  = isset($transaksi);
    $old     = fn($key, $fallback = '') => old($key, $isEdit ? $transaksi->{$key} : $fallback);
    $input   = 'w-full rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm text-gray-800
                placeholder-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition';
    $label   = 'block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-1.5';
    $errCls  = 'mt-1 text-xs text-red-500 font-medium';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('transaksis.update', $transaksi) : route('transaksis.store') }}"
      class="space-y-5">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- Pasar --}}
    <div>
        <label class="{{ $label }}">Pasar <span class="text-red-400">*</span></label>
        <select name="pasar_id" class="{{ $input }} @error('pasar_id') border-red-400 @enderror">
            <option value="">-- Pilih Pasar --</option>
            @foreach($pasarList as $p)
                <option value="{{ $p->id }}" {{ (string)$old('pasar_id') === (string)$p->id ? 'selected' : '' }}>
                    {{ $p->nama_pasar }} — {{ $p->kantor_cabang }}
                </option>
            @endforeach
        </select>
        @error('pasar_id') <p class="{{ $errCls }}">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal --}}
    <div>
        <label class="{{ $label }}">Tanggal <span class="text-red-400">*</span></label>
        <input type="date" name="tanggal"
               value="{{ $isEdit ? $transaksi->tanggal->format('Y-m-d') : old('tanggal', date('Y-m-d')) }}"
               max="{{ date('Y-m-d') }}"
               class="{{ $input }} @error('tanggal') border-red-400 @enderror">
        @error('tanggal') <p class="{{ $errCls }}">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        {{-- Jumlah KG --}}
        <div>
            <label class="{{ $label }}">Jumlah (kg) <span class="text-red-400">*</span></label>
            <input type="number" name="jumlah_kg" value="{{ $old('jumlah_kg') }}"
                   step="0.1" min="0.1" max="999" placeholder="7.5"
                   class="{{ $input }} font-mono @error('jumlah_kg') border-red-400 @enderror">
            @error('jumlah_kg') <p class="{{ $errCls }}">{{ $message }}</p> @enderror
        </div>

        {{-- Harga Jual --}}
        <div>
            <label class="{{ $label }}">Harga Jual (Rp) <span class="text-red-400">*</span></label>
            <input type="number" name="harga_jual" value="{{ $old('harga_jual') }}"
                   min="1" placeholder="61000"
                   class="{{ $input }} font-mono @error('harga_jual') border-red-400 @enderror">
            @error('harga_jual') <p class="{{ $errCls }}">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Keterangan --}}
    <div>
        <label class="{{ $label }}">Keterangan <span class="text-blue-300 text-[10px] normal-case tracking-normal font-normal">(opsional)</span></label>
        <textarea name="keterangan" rows="3" placeholder="Mis: Penjualan harian, Operasi pasar…"
                  class="{{ $input }} resize-none @error('keterangan') border-red-400 @enderror">{{ $old('keterangan') }}</textarea>
        @error('keterangan') <p class="{{ $errCls }}">{{ $message }}</p> @enderror
    </div>

    {{-- Preview omzet --}}
    <div id="preview-box" class="hidden rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 flex items-center justify-between">
        <span class="text-xs text-blue-500 font-semibold">Estimasi Omzet</span>
        <span id="preview-omzet" class="font-mono font-bold text-blue-700 text-sm">Rp 0</span>
    </div>

    <div class="flex items-center gap-3 pt-1">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 transition"
                style="background:linear-gradient(135deg,#1e40af,#2563eb)">
            {{ $isEdit ? '💾 Simpan Perubahan' : '➕ Tambah Transaksi' }}
        </button>
        <a href="{{ route('transaksis.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold text-blue-600 border border-blue-200 hover:bg-blue-50 transition">
            Batal
        </a>
    </div>
</form>

<script>
    const kgInput    = document.querySelector('[name="jumlah_kg"]');
    const hargaInput = document.querySelector('[name="harga_jual"]');
    const previewBox = document.getElementById('preview-box');
    const previewVal = document.getElementById('preview-omzet');

    function updatePreview() {
        const kg    = parseFloat(kgInput.value) || 0;
        const harga = parseInt(hargaInput.value) || 0;
        const omzet = kg * harga;
        if (kg > 0 && harga > 0) {
            previewBox.classList.remove('hidden');
            previewVal.textContent = 'Rp ' + omzet.toLocaleString('id-ID');
        } else {
            previewBox.classList.add('hidden');
        }
    }
    kgInput.addEventListener('input', updatePreview);
    hargaInput.addEventListener('input', updatePreview);
    updatePreview();
</script>