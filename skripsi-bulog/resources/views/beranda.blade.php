<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Jawa Barat - Monitoring SPHP</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; overflow: hidden; }
        
        .navbar-custom { 
            background: linear-gradient(90deg, #1a237e 0%, #283593 100%); 
            color: white; 
            height: 100px; 
            padding: 0 30px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .sidebar { 
            background: white; 
            border-radius: 15px; 
            padding: 20px; 
            height: calc(100vh - 130px); 
            overflow-y: auto; 
        }
        
        #map { 
            height: calc(100vh - 130px); 
            border-radius: 15px; 
            border: 5px solid white; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        }

        .logo-nav { height: 70px; mix-blend-mode: screen; object-fit: contain; }
        
        .item-pasar { 
            border-left: 4px solid #1a237e !important; 
            transition: 0.3s;
            cursor: pointer;
        }
        
        .item-pasar:hover {
            transform: translateX(5px);
            background-color: #f0f4ff;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #1a237e; border-radius: 10px; }
    </style>
</head>
<body>

<nav class="navbar-custom d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <img src="{{ asset('image/logo-bulog-putih.png') }}" alt="Logo Bulog" class="logo-nav">
        <div class="d-flex flex-column ms-3">
            <h4 class="mb-0 fw-bold" style="letter-spacing: 1px;">DASHBOARD JAWA BARAT</h4>
            <small class="opacity-75">
                <i class="fas fa-user-circle me-1"></i>
                User: {{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})
            </small>
        </div>
    </div>
    
    <div class="d-flex gap-5 text-center d-none d-xl-flex">
        <div><h4 class="mb-0 fw-bold text-warning">{{ number_format($totalSphp) }}</h4><small class="opacity-75 text-white">Total Penyaluran (Kg)</small></div>
        <div><h4 class="mb-0 fw-bold text-white">{{ $jumlahPasar }}</h4><small class="opacity-75 text-white">Total Pasar</small></div>
        <div><h4 class="mb-0 fw-bold text-white">{{ $jumlahCabang }}</h4><small class="opacity-75 text-white">Cabang</small></div>
    </div>

  
</nav>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar shadow-sm">
                <div class="mb-4">
                    <label class="fw-bold text-primary mb-2 small"><i class="fas fa-filter me-2"></i>Filter Kantor Cabang</label>
                    <select class="form-select border-primary shadow-sm" id="pilihCabang">
                        <option value="semua">Semua Kantor Cabang</option>
                        @foreach($semuaPasar->pluck('kantor_cabang')->unique() as $cabang)
                            <option value="{{ strtoupper($cabang) }}">{{ strtoupper($cabang) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="fw-bold text-primary mb-2 small"><i class="fas fa-chart-line me-2"></i>Status Pantauan</label>
                    <div class="card bg-warning text-dark border-0 shadow-sm mb-2" style="border-radius: 12px;">
                        <div class="card-body text-center py-2">
                            <h2 class="fw-bold mb-0">{{ $pasarPantauan }}</h2>
                            <small class="fw-bold">Pasar Aktif SPHP</small>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold border-bottom pb-2 text-primary mb-3">📋 DAFTAR PASAR</h6>
                <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                    @foreach($semuaPasar as $p)
                    <div class="card mb-2 border-0 shadow-sm item-pasar" 
                         data-cabang="{{ strtoupper($p->kantor_cabang) }}"
                         data-bs-toggle="modal" 
                         data-bs-target="#modalPasar{{ $p->id }}">
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.8rem;">{{ $p->nama_pasar }}</h6>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $p->kantor_cabang }}</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success">
                                    {{ number_format($p->transaksis_sum_jumlah_kg ?? 0) }} Kg
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modalPasar{{ $p->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header bg-primary text-white">
                                    <h6 class="modal-title fw-bold">{{ $p->nama_pasar }}</h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="small text-muted mb-3"><i class="fas fa-info-circle me-1"></i> Riwayat penyaluran SPHP untuk pasar ini.</p>
                                    <div class="table-responsive">
                                        <table class="table table-sm small">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jumlah</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($p->transaksis as $trx)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') }}</td>
                                                    <td class="fw-bold text-primary">{{ number_format($trx->jumlah_kg) }} Kg</td>
                                                    <td>{{ $trx->keterangan }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="3" class="text-center py-3">Belum ada transaksi</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div id="map"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-6.9175, 107.6191], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var pasars = @json($semuaPasar);
    var semuaMarker = [];

    pasars.forEach(function(p) {
        var lat = p.latitude || p.lat;
        var lng = p.longitude || p.lng;
        
        if(lat && lng) {
            var marker = L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map);
            var totalTrx = p.transaksis_sum_jumlah_kg ? p.transaksis_sum_jumlah_kg.toLocaleString() : 0;
            
            marker.bindPopup(`
                <div class="text-center p-1">
                    <h6 class="fw-bold mb-1">${p.nama_pasar}</h6>
                    <span class="badge bg-primary mb-2">${p.kantor_cabang}</span><br>
                    <div class="border-top pt-2">
                        <small class="text-muted">Total Penyaluran:</small><br>
                        <strong class="text-success h6">${totalTrx} Kg</strong>
                    </div>
                </div>
            `);
            marker.infoCabang = p.kantor_cabang ? p.kantor_cabang.toString().toUpperCase().trim() : "";
            semuaMarker.push(marker);
        }
    });

    document.getElementById('pilihCabang').addEventListener('change', function() {
        var cabangDipilih = this.value.toUpperCase().trim();
        var totalMuncul = 0;

        semuaMarker.forEach(function(m) {
            if (cabangDipilih === "SEMUA" || m.infoCabang === cabangDipilih) {
                if (!map.hasLayer(m)) { m.addTo(map); }
                totalMuncul++;
            } else {
                map.removeLayer(m);
            }
        });

        document.querySelectorAll('.item-pasar').forEach(function(el) {
            var cabangItem = el.getAttribute('data-cabang') ? el.getAttribute('data-cabang').toUpperCase().trim() : "";
            el.style.display = (cabangDipilih === "SEMUA" || cabangItem === cabangDipilih) ? "block" : "none";
        });
    });
</script>
</body>
</html>