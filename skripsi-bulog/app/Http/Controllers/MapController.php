<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index(Request $request)
    {
        $kancab     = $request->input('kantor_cabang');
        $kabupaten  = $request->input('kabupaten');

        // ── Pasars dengan data transaksi terbaru ───────────────────────────
        $pasars = Pasar::query()
            ->when($kancab,    fn($q) => $q->where('kantor_cabang', $kancab))
            ->when($kabupaten, fn($q) => $q->where('kabupaten', $kabupaten))
            ->withCount('transaksis')
            ->with(['transaksis' => fn($q) => $q
                ->selectRaw('pasar_id, SUM(jumlah_kg) as total_kg, AVG(harga_jual) as avg_harga, MAX(tanggal) as last_tanggal')
                ->groupBy('pasar_id')
            ])
            ->get();

        // ── Summary stats ──────────────────────────────────────────────────
        $totalPasar  = Pasar::count();
        $totalKancab = Pasar::distinct('kantor_cabang')->count('kantor_cabang');

        $globalStats = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->selectRaw('
                SUM(transaksis.jumlah_kg)           AS total_kg,
                COUNT(transaksis.id)                 AS total_transaksi,
                COUNT(DISTINCT transaksis.pasar_id)  AS active_pasar,
                AVG(transaksis.harga_jual)           AS avg_harga
            ')
            ->first();

        // ── Per-kancab summary untuk legend ───────────────────────────────
        $kancabStats = DB::table('pasars')
            ->leftJoin('transaksis', 'transaksis.pasar_id', '=', 'pasars.id')
            ->selectRaw('
                pasars.kantor_cabang,
                COUNT(DISTINCT pasars.id)           AS total_pasar,
                COUNT(transaksis.id)                 AS total_transaksi,
                SUM(transaksis.jumlah_kg)           AS total_kg
            ')
            ->groupBy('pasars.kantor_cabang')
            ->orderByDesc('total_kg')
            ->get();

        // ── Dropdown lists ─────────────────────────────────────────────────
        $kancabList    = Pasar::kancabOptions();
        $kabupatenList = Pasar::distinct()->orderBy('kabupaten')->pluck('kabupaten');

        // ── GeoJSON untuk Leaflet ──────────────────────────────────────────
        $geoJson = [
            'type'     => 'FeatureCollection',
            'features' => $pasars->map(function ($p) {
                $tx = $p->transaksis->first();
                return [
                    'type'     => 'Feature',
                    'geometry' => [
                        'type'        => 'Point',
                        'coordinates' => [(float) $p->longitude, (float) $p->latitude],
                    ],
                    'properties' => [
                        'id'              => $p->id,
                        'nama_pasar'      => $p->nama_pasar,
                        'kantor_cabang'   => $p->kantor_cabang,
                        'kabupaten'       => $p->kabupaten,
                        'total_transaksi' => $p->transaksis_count,
                        'total_kg'        => $tx ? round($tx->total_kg, 1) : 0,
                        'avg_harga'       => $tx ? round($tx->avg_harga) : 0,
                        'last_tanggal'    => $tx ? $tx->last_tanggal : null,
                        'url_show'        => route('pasars.show', $p->id),
                    ],
                ];
            })->values()->toArray(),
        ];

        return view('maps.index', compact(
            'geoJson',
            'kancabStats',
            'kancabList',
            'kabupatenList',
            'totalPasar',
            'totalKancab',
            'globalStats',
            'kancab',
            'kabupaten'
        ));
    }
}