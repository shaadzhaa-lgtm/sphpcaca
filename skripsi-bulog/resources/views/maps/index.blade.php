@extends('layouts.app')

@section('title', 'Peta Lokasi Pasar')
@section('page-title', 'Peta Lokasi Pasar')
@section('breadcrumb')<span class="text-blue-600 font-semibold">Peta</span>@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map { height: calc(100vh - 220px); min-height: 480px; border-radius: 16px; z-index: 0; }

    /* Custom marker */
    .custom-marker {
        width: 30px; height: 30px;
        border-radius: 50% 50% 50% 0;
        transform: rotate(-45deg);
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    }
    .custom-marker-inner {
        position: absolute; inset: 0;
        display: flex; align-items: center; justify-content: center;
        transform: rotate(45deg);
        font-size: 11px; font-weight: 700; color: white;
    }

    /* Popup styling */
    .leaflet-popup-content-wrapper {
        border-radius: 14px !important;
        box-shadow: 0 8px 30px rgba(30,64,175,0.18) !important;
        border: 1px solid #dbeafe;
        padding: 0 !important;
        overflow: hidden;
    }
    .leaflet-popup-content { margin: 0 !important; width: 260px !important; }
    .leaflet-popup-tip { background: white !important; }
    .leaflet-popup-close-button {
        color: #93c5fd !important;
        top: 8px !important; right: 10px !important;
        font-size: 18px !important;
        z-index: 10;
    }

    /* Panel scroll */
    #kancab-list { max-height: 260px; overflow-y: auto; }
    #pasar-list-panel { max-height: 320px; overflow-y: auto; }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-thumb { background: #93c5fd; border-radius: 2px; }
</style>
@endpush

@section('content')

@php
$kancabColors = [
    'KANCAB BOGOR'     => ['hex' => '#059669', 'light' => '#d1fae5', 'text' => '#065f46'],
    'KANCAB CIANJUR'   => ['hex' => '#2563eb', 'light' => '#dbeafe', 'text' => '#1e40af'],
    'KANCAB BANDUNG'   => ['hex' => '#7c3aed', 'light' => '#ede9fe', 'text' => '#5b21b6'],
    'KANCAB CIAMIS'    => ['hex' => '#d97706', 'light' => '#fef3c7', 'text' => '#92400e'],
    'KANCAB CIREBON'   => ['hex' => '#ea580c', 'light' => '#ffedd5', 'text' => '#9a3412'],
    'KANCAB INDRAMAYU' => ['hex' => '#0891b2', 'light' => '#cffafe', 'text' => '#164e63'],
    'KANCAB SUBANG'    => ['hex' => '#4f46e5', 'light' => '#e0e7ff', 'text' => '#3730a3'],
    'KANCAB KARAWANG'  => ['hex' => '#dc2626', 'light' => '#fee2e2', 'text' => '#991b1b'],
];
@endphp

{{-- ── Filter bar ────────────────────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('maps.index') }}"
      class="bg-white rounded-2xl shadow-sm border border-blue-100 px-5 py-3.5 mb-5
             flex flex-wrap items-end gap-3">
    <div class="flex-1 min-w-44">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Kantor Cabang</label>
        <select name="kantor_cabang"
                class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            <option value="">Semua Kantor Cabang</option>
            @foreach($kancabList as $k)
                <option value="{{ $k }}" {{ $kancab === $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex-1 min-w-44">
        <label class="block text-[10px] font-semibold text-blue-500 uppercase tracking-wider mb-1.5">Kabupaten / Kota</label>
        <select name="kabupaten"
                class="w-full rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            <option value="">Semua Kabupaten</option>
            @foreach($kabupatenList as $kb)
                <option value="{{ $kb }}" {{ $kabupaten === $kb ? 'selected' : '' }}>{{ $kb }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit"
                class="px-5 py-2 rounded-xl text-sm font-bold text-white shadow-sm hover:shadow-md hover:-translate-y-0.5 transition"
                style="background:linear-gradient(135deg,#1e40af,#2563eb)">
            Filter
        </button>
        <a href="{{ route('maps.index') }}"
           class="px-4 py-2 rounded-xl text-sm font-semibold text-blue-500 border border-blue-200 hover:bg-blue-50 transition">
            Reset
        </a>
    </div>
    {{-- KPI pills --}}
    <div class="flex items-center gap-2 ml-auto flex-wrap">
        @foreach([
            ['label' => 'Total Pasar',    'val' => number_format($totalPasar)],
            ['label' => 'Total Transaksi','val' => number_format($globalStats->total_transaksi ?? 0)],
            ['label' => 'Total Volume',   'val' => number_format($globalStats->total_kg ?? 0, 1, ',', '.') . ' kg'],
        ] as $pill)
        <div class="bg-blue-50 border border-blue-100 rounded-xl px-3 py-1.5 text-center min-w-24">
            <p class="text-[10px] text-blue-400 font-semibold">{{ $pill['label'] }}</p>
            <p class="text-sm font-extrabold text-blue-700">{{ $pill['val'] }}</p>
        </div>
        @endforeach
    </div>
</form>

{{-- ── Main layout: Map + Sidebar ───────────────────────────────────────────── --}}
<div class="flex gap-5" style="align-items: flex-start;">

    {{-- Map --}}
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-50 flex items-center justify-between bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                         style="background:linear-gradient(135deg,#1e40af,#2563eb)">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-blue-900 text-sm">Peta Sebaran Pasar</p>
                        <p class="text-[10px] text-blue-400" id="map-count">Memuat marker…</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="resetView()"
                            class="text-xs text-blue-500 font-semibold border border-blue-200 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                        </svg>
                        Reset View
                    </button>
                </div>
            </div>
            <div id="map"></div>
        </div>
    </div>

    {{-- Sidebar ──────────────────────────────────────────────────────────────── --}}
    <div class="w-72 shrink-0 space-y-4">

        {{-- Per Kancab stats --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-50" style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
                <p class="font-bold text-blue-900 text-sm">Volume per Kancab</p>
                <p class="text-[10px] text-blue-400 mt-0.5">Klik untuk sorot di peta</p>
            </div>
            <div id="kancab-list" class="p-3 space-y-2">
                @php $maxKg = $kancabStats->max('total_kg') ?: 1; @endphp
                @foreach($kancabStats as $ks)
                @php
                    $c    = $kancabColors[$ks->kantor_cabang] ?? ['hex'=>'#64748b','light'=>'#f1f5f9','text'=>'#334155'];
                    $pct  = round($ks->total_kg / $maxKg * 100);
                    $label = str_replace('KANCAB ', '', $ks->kantor_cabang);
                @endphp
                <div class="kancab-item cursor-pointer rounded-xl p-2.5 hover:shadow-sm transition-all border border-transparent hover:border-blue-100"
                     data-kancab="{{ $ks->kantor_cabang }}"
                     onclick="filterByKancab('{{ $ks->kantor_cabang }}')">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full shrink-0"
                                  style="background:{{ $c['hex'] }}"></span>
                            <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                        </div>
                        <span class="text-[10px] font-bold" style="color:{{ $c['text'] }}">
                            {{ number_format($ks->total_kg, 0, ',', '.') }} kg
                        </span>
                    </div>
                    <div class="w-full rounded-full h-1.5" style="background:{{ $c['light'] }}">
                        <div class="h-1.5 rounded-full transition-all" style="width:{{ $pct }}%;background:{{ $c['hex'] }}"></div>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-[10px] text-gray-400">{{ $ks->total_pasar }} pasar</span>
                        <span class="text-[10px] text-gray-400">{{ number_format($ks->total_transaksi) }} tx</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="px-3 pb-3">
                <button onclick="showAllKancab()"
                        class="w-full text-xs text-blue-500 font-semibold border border-blue-200 hover:bg-blue-50 py-1.5 rounded-lg transition">
                    Tampilkan Semua
                </button>
            </div>
        </div>

        {{-- Pasar list (updates on map interaction) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-blue-50 flex items-center justify-between"
                 style="background:linear-gradient(135deg,#eff6ff,#dbeafe)">
                <p class="font-bold text-blue-900 text-sm">Daftar Pasar</p>
                <span id="pasar-count-badge"
                      class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">
                    —
                </span>
            </div>
            <div id="pasar-list-panel" class="p-2 space-y-1">
                <p class="text-xs text-blue-300 text-center py-6">Klik kancab atau marker untuk melihat daftar</p>
            </div>
        </div>

        {{-- Legend --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-4">
            <p class="font-bold text-blue-900 text-xs mb-3 uppercase tracking-wider">Legenda Marker</p>
            <div class="space-y-2">
                @foreach($kancabColors as $kName => $c)
                <div class="flex items-center gap-2.5">
                    <div class="w-4 h-4 rounded-full shrink-0 border-2 border-white shadow-sm"
                         style="background:{{ $c['hex'] }}"></div>
                    <span class="text-xs text-gray-600">{{ str_replace('KANCAB ', '', $kName) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<script>
// ── GeoJSON data from Laravel ─────────────────────────────────────────────────
const GEO = @json($geoJson);

// ── Kancab color map ─────────────────────────────────────────────────────────
const KANCAB_COLORS = @json($kancabColors);

function getColor(kancab) {
    return (KANCAB_COLORS[kancab] || { hex: '#64748b' }).hex;
}

// ── Map init ─────────────────────────────────────────────────────────────────
const map = L.map('map', {
    center: [-6.9, 107.6],
    zoom: 8,
    zoomControl: false,
});

L.control.zoom({ position: 'bottomright' }).addTo(map);

// Tile layers
const tiles = {
    'Street': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }),
    'Satelit': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri',
        maxZoom: 19,
    }),
    'Topografi': L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenTopoMap',
        maxZoom: 17,
    }),
};
tiles['Street'].addTo(map);
L.control.layers(tiles, {}, { position: 'topright' }).addTo(map);

// ── Layer group (tanpa clustering) ───────────────────────────────────────────
const cluster = L.layerGroup();

// ── Build markers ─────────────────────────────────────────────────────────────
const allMarkers = [];

GEO.features.forEach(f => {
    const p   = f.properties;
    const lat = f.geometry.coordinates[1];
    const lng = f.geometry.coordinates[0];
    const color = getColor(p.kantor_cabang);
    const label = p.kantor_cabang.replace('KANCAB ', '');

    // Custom pin icon
    const icon = L.divIcon({
        html: `<div style="
            width:28px;height:28px;border-radius:50% 50% 50% 0;
            background:${color};
            transform:rotate(-45deg);
            border:2.5px solid white;
            box-shadow:0 2px 8px rgba(0,0,0,0.3);
            position:relative;
        ">
            <div style="
                position:absolute;inset:0;
                display:flex;align-items:center;justify-content:center;
                transform:rotate(45deg);
                font-size:9px;font-weight:700;color:white;line-height:1;
            ">${label.slice(0,3)}</div>
        </div>`,
        className: '',
        iconSize: [28, 36],
        iconAnchor: [14, 36],
        popupAnchor: [0, -36],
    });

    const marker = L.marker([lat, lng], { icon });

    // Popup HTML
    const lastDate = p.last_tanggal
        ? new Date(p.last_tanggal).toLocaleDateString('id-ID', { day:'2-digit', month:'short', year:'numeric' })
        : '—';

    const popupHtml = `
        <div>
            <div style="
                padding:14px 16px 10px;
                background:linear-gradient(135deg,#1e3a8a,#2563eb);
            ">
                <p style="color:#bfdbfe;font-size:9px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 2px;">
                    ${p.kantor_cabang}
                </p>
                <p style="color:#fff;font-weight:700;font-size:13px;margin:0;line-height:1.3;">
                    ${p.nama_pasar}
                </p>
                <p style="color:#93c5fd;font-size:10px;margin:4px 0 0;">${p.kabupaten}</p>
            </div>
            <div style="padding:12px 16px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;">
                    <div style="background:#eff6ff;border-radius:10px;padding:8px 10px;">
                        <p style="font-size:9px;color:#60a5fa;font-weight:600;margin:0 0 2px;text-transform:uppercase;">Total Transaksi</p>
                        <p style="font-size:15px;font-weight:800;color:#1e40af;margin:0;">${p.total_transaksi.toLocaleString('id-ID')}</p>
                    </div>
                    <div style="background:#eff6ff;border-radius:10px;padding:8px 10px;">
                        <p style="font-size:9px;color:#60a5fa;font-weight:600;margin:0 0 2px;text-transform:uppercase;">Total Volume</p>
                        <p style="font-size:15px;font-weight:800;color:#1e40af;margin:0;">${Number(p.total_kg).toLocaleString('id-ID')} <span style="font-size:10px;font-weight:600;">kg</span></p>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:11px;color:#6b7280;border-top:1px solid #eff6ff;padding-top:8px;margin-bottom:10px;">
                    <span>Avg harga: <strong style="color:#374151;">Rp ${Number(p.avg_harga).toLocaleString('id-ID')}</strong></span>
                    <span style="font-size:10px;">Terakhir: ${lastDate}</span>
                </div>
                <a href="${p.url_show}"
                   style="
                       display:block;text-align:center;padding:7px;border-radius:10px;
                       background:linear-gradient(135deg,#1e40af,#2563eb);
                       color:#fff;font-weight:700;font-size:12px;text-decoration:none;
                   ">
                    Lihat Detail Pasar →
                </a>
            </div>
        </div>`;

    marker.bindPopup(popupHtml, { maxWidth: 280, minWidth: 260 });
    marker._pasarData = p;
    allMarkers.push(marker);
    cluster.addLayer(marker);
});

map.addLayer(cluster);

// Update counter
document.getElementById('map-count').textContent =
    GEO.features.length + ' pasar ditampilkan';

// ── Filter by kancab ──────────────────────────────────────────────────────────
let activeKancab = null;

function filterByKancab(kancab) {
    // Toggle
    if (activeKancab === kancab) {
        showAllKancab();
        return;
    }
    activeKancab = kancab;

    // Highlight sidebar item
    document.querySelectorAll('.kancab-item').forEach(el => {
        const isActive = el.dataset.kancab === kancab;
        el.style.background = isActive ? '#eff6ff' : '';
        el.style.borderColor = isActive ? '#bfdbfe' : 'transparent';
    });

    // Filter markers
    cluster.clearLayers();
    const filtered = allMarkers.filter(m => m._pasarData.kantor_cabang === kancab);
    filtered.forEach(m => cluster.addLayer(m));

    // Fit bounds
    if (filtered.length > 0) {
        const bounds = L.latLngBounds(filtered.map(m => m.getLatLng()));
        map.fitBounds(bounds, { padding: [40, 40] });
    }

    // Update pasar list panel
    renderPasarList(filtered.map(m => m._pasarData), kancab);
    document.getElementById('map-count').textContent =
        filtered.length + ' pasar — ' + kancab.replace('KANCAB ', '');
}

function showAllKancab() {
    activeKancab = null;
    cluster.clearLayers();
    allMarkers.forEach(m => cluster.addLayer(m));
    map.setView([-6.9, 107.6], 8);

    document.querySelectorAll('.kancab-item').forEach(el => {
        el.style.background  = '';
        el.style.borderColor = 'transparent';
    });

    document.getElementById('pasar-list-panel').innerHTML =
        '<p class="text-xs text-blue-300 text-center py-6">Klik kancab atau marker untuk melihat daftar</p>';
    document.getElementById('pasar-count-badge').textContent = '—';
    document.getElementById('map-count').textContent = GEO.features.length + ' pasar ditampilkan';
}

// ── Render pasar list ─────────────────────────────────────────────────────────
function renderPasarList(pasars, kancab) {
    const color = getColor(kancab);
    const panel = document.getElementById('pasar-list-panel');
    document.getElementById('pasar-count-badge').textContent = pasars.length;

    if (pasars.length === 0) {
        panel.innerHTML = '<p class="text-xs text-blue-300 text-center py-6">Tidak ada pasar</p>';
        return;
    }

    // Sort by volume desc
    const sorted = [...pasars].sort((a, b) => b.total_kg - a.total_kg);

    panel.innerHTML = sorted.map((p, i) => `
        <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl hover:bg-blue-50 cursor-pointer transition"
             onclick="focusPasar(${GEO.features.findIndex(f => f.properties.nama_pasar === p.nama_pasar)})">
            <span style="
                width:20px;height:20px;border-radius:6px;
                background:${color}18;color:${color};
                font-size:9px;font-weight:700;
                display:flex;align-items:center;justify-content:center;
                flex-shrink:0;
            ">${i + 1}</span>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-gray-700 truncate">${p.nama_pasar}</p>
                <p class="text-[10px] text-gray-400">${Number(p.total_kg).toLocaleString('id-ID')} kg · ${Number(p.total_transaksi).toLocaleString('id-ID')} tx</p>
            </div>
            <svg style="width:12px;height:12px;color:#93c5fd;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
            </svg>
        </div>
    `).join('');
}

// ── Focus on specific pasar marker ───────────────────────────────────────────
function focusPasar(featureIdx) {
    if (featureIdx < 0) return;
    const feat = GEO.features[featureIdx];
    const marker = allMarkers.find(m => m._pasarData.nama_pasar === feat.properties.nama_pasar);
    if (!marker) return;

    const latlng = marker.getLatLng();
    map.setView(latlng, 14, { animate: true });

    // Open popup after zoom
    setTimeout(() => marker.openPopup(), 400);
}

// ── Reset view ────────────────────────────────────────────────────────────────
function resetView() {
    showAllKancab();
}

// ── Scale control ─────────────────────────────────────────────────────────────
L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);
</script>
@endpush