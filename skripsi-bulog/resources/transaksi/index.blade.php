@extends('layouts.app')

@section('content')
<div class="container mt-4 pb-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">RIWAYAT TRANSAKSI SPHP</h3>
            <p class="text-muted">Data penyaluran harian, bulanan, dan per kantor cabang</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary fw-bold px-3">
                <i class="fas fa-arrow-left me-1"></i> DASHBOARD
            </a>
            <a href="{{ route('transaksi.create') }}" class="btn btn-success fw-bold px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> TAMBAH TRANSAKSI
            </a>
        </div>
    </div>

    {{-- TABEL 1: DETAIL HARIAN --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list me-2 text-primary"></i>Detail Transaksi Per Tanggal</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center py-3">No</th>
                            <th class="py-3">Nama Pasar</th>
                            <th class="py-3 text-center">Tanggal</th>
                            <th class="py-3 text-center">Jumlah (Kg)</th>
                            <th class="py-3 text-center">Harga</th>
                            <th class="py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr class="align-middle">
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-primary">{{ $t->pasar->nama_pasar ?? 'N/A' }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-3 py-2" style="font-size: 0.9rem;">
                                    {{ number_format($t->jumlah_kg) }} Kg
                                </span>
                            </td>
                            <td class="text-center fw-bold text-secondary">Rp {{ number_format($t->harga_jual) }}</td>
                            <td><small class="text-muted">{{ $t->keterangan }}</small></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Data harian kosong.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL 2: REKAP BULANAN --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
        <div class="card-header bg-primary py-3">
            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Rekapitulasi Penyaluran Per Bulan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-secondary border-bottom">
                        <tr class="text-center">
                            <th class="py-3" width="80">No</th>
                            <th class="py-3 text-start px-4">Bulan & Tahun</th>
                            <th class="py-3">Total Volume SPHP</th>
                            <th class="py-3">Jumlah Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapBulanan as $rb)
                        <tr class="text-center align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start px-4 fw-bold text-dark">
                                {{ \Carbon\Carbon::create($rb->tahun, $rb->bulan)->translatedFormat('F Y') }}
                            </td>
                            <td>
                                <h5 class="mb-0 text-success fw-bold">{{ number_format($rb->total_kg) }} Kg</h5>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-secondary px-3 py-2">
                                    {{ $rb->total_transaksi }} Penyaluran
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted">Data rekap bulanan belum tersedia.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL 3: REKAP PER KANTOR CABANG --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-dark py-3">
            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-map-marker-alt me-2 text-warning"></i>Rekapitulasi Per Kantor Cabang (KANCAB)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-secondary border-bottom">
                        <tr class="text-center">
                            <th class="py-3" width="80">No</th>
                            <th class="py-3 text-start px-4">Nama Kantor Cabang</th>
                            <th class="py-3">Total Volume Tersalurkan</th>
                            <th class="py-3">Frekuensi Penyaluran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapCabang as $rc)
                        <tr class="text-center align-middle">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start px-4 fw-bold text-dark text-uppercase">
                                {{ $rc->kantor_cabang ?? 'CABANG TIDAK TERDEFINISI' }}
                            </td>
                            <td>
                                <span class="text-primary fw-bold" style="font-size: 1.1rem;">
                                    {{ number_format($rc->total_kg) }} Kg
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-3">
                                    {{ $rc->total_transaksi }} Kali
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <p class="text-muted mb-0">Data per cabang belum masuk.</p>
                                <small>Pastikan kolom 'kantor_cabang' di tabel pasars sudah terisi.</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    {{-- Grand Total Jabar --}}
                    @if($rekapCabang->count() > 0)
                    <tfoot class="table-light fw-bold border-top">
                        <tr class="text-center">
                            <td colspan="2" class="text-end px-4">TOTAL KESELURUHAN (JAWA BARAT):</td>
                            <td class="text-success" style="font-size: 1.25rem;">
                                {{ number_format($rekapCabang->sum('total_kg')) }} Kg
                            </td>
                            <td class="text-muted">{{ $rekapCabang->sum('total_transaksi') }} Transaksi</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection