<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi SPHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color: #f8f9fa;">

<nav class="navbar navbar-dark bg-primary shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('beranda') }}">
            <i class="fas fa-arrow-left me-2"></i> KEMBALI KE DASHBOARD
        </a>
    </div>
</nav>

<div class="container pb-5">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">RIWAYAT TRANSAKSI SPHP</h3>
        <a href="{{ route('transaksi.create') }}" class="btn btn-success shadow-sm">
            <i class="fas fa-plus me-1"></i> TAMBAH TRANSAKSI
        </a>
    </div>

    {{-- TABEL 1: DETAIL PER TANGGAL --}}
    <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-list me-2"></i>Detail Transaksi Per Tanggal</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3">No</th>
                            <th class="py-3">Nama Pasar</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3 text-center">Jumlah (Kg)</th>
                            <th class="py-3">Harga</th>
                            <th class="py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksi as $t)
                        <tr>
                            <td class="ps-4">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-primary">{{ $t->pasar->nama_pasar ?? 'Pasar Tidak Dikenal' }}</td>
                            <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark px-3 py-2" style="font-size: 0.9rem;">
                                    {{ number_format($t->jumlah_kg) }} Kg
                                </span>
                            </td>
                            <td>Rp {{ number_format($t->harga_jual) }}</td>
                            <td><small class="text-muted">{{ $t->keterangan }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TABEL 2: REKAP BULANAN (YANG BARU) --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-primary py-3">
            <h5 class="fw-bold mb-0 text-white"><i class="fas fa-calendar-alt me-2"></i>Rekapitulasi Penyaluran Per Bulan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="text-center text-secondary">
                            <th class="py-3">No</th>
                            <th class="py-3 text-start ps-4">Bulan & Tahun</th>
                            <th class="py-3">Total Penyaluran (Kg)</th>
                            <th class="py-3">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapBulanan as $rb)
                        <tr class="text-center">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start ps-4 fw-bold">
                                {{ \Carbon\Carbon::create($rb->tahun, $rb->bulan)->translatedFormat('F Y') }}
                            </td>
                            <td>
                                <h5 class="mb-0 text-success fw-bold">{{ number_format($rb->total_kg) }} Kg</h5>
                            </td>
                            <td>
                                <span class="badge rounded-pill bg-secondary px-3">{{ $rb->total_transaksi }} Kali Penyaluran</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">Data per bulan belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>