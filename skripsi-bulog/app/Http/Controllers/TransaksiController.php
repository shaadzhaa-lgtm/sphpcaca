<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        // ── Filters ────────────────────────────────────────────────────────────
        $pasarId  = $request->input('pasar_id');
        $kancab   = $request->input('kantor_cabang');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());

        // ── KPI: overall summary ────────────────────────────────────────────
        $kpiBase = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->whereBetween('transaksis.tanggal', [$dateFrom, $dateTo])
            ->when($pasarId, fn($q) => $q->where('transaksis.pasar_id', $pasarId))
            ->when($kancab,  fn($q) => $q->where('pasars.kantor_cabang', $kancab));

        $kpi = (clone $kpiBase)->selectRaw('
            COUNT(transaksis.id)                  AS total_transaksi,
            SUM(transaksis.jumlah_kg)             AS total_kg,
            AVG(transaksis.jumlah_kg)             AS avg_kg,
            MAX(transaksis.jumlah_kg)             AS max_kg,
            MIN(transaksis.jumlah_kg)             AS min_kg,
            AVG(transaksis.harga_jual)            AS avg_harga,
            COUNT(DISTINCT transaksis.pasar_id)   AS active_pasar,
            COUNT(DISTINCT transaksis.tanggal)    AS hari_aktif
        ')->first();

        // ── Performance per Kantor Cabang ───────────────────────────────────
        $kancabPerf = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->whereBetween('transaksis.tanggal', [$dateFrom, $dateTo])
            ->when($pasarId, fn($q) => $q->where('transaksis.pasar_id', $pasarId))
            ->when($kancab,  fn($q) => $q->where('pasars.kantor_cabang', $kancab))
            ->selectRaw('
                pasars.kantor_cabang,
                COUNT(transaksis.id)                    AS total_transaksi,
                SUM(transaksis.jumlah_kg)               AS total_kg,
                AVG(transaksis.jumlah_kg)               AS avg_kg,
                AVG(transaksis.harga_jual)              AS avg_harga,
                MAX(transaksis.harga_jual)              AS max_harga,
                MIN(transaksis.harga_jual)              AS min_harga,
                COUNT(DISTINCT transaksis.pasar_id)     AS pasar_aktif,
                COUNT(DISTINCT transaksis.tanggal)      AS hari_aktif
            ')
            ->groupBy('pasars.kantor_cabang')
            ->orderByDesc('total_kg')
            ->get();

        // Gabungkan dengan jumlah pasar terdaftar
        $pasarPerKancab = Pasar::selectRaw('kantor_cabang, COUNT(*) AS total')
            ->groupBy('kantor_cabang')
            ->pluck('total', 'kantor_cabang');

        $totalKgAll = $kancabPerf->sum('total_kg') ?: 1;

        $kancabPerf = $kancabPerf->map(function ($row) use ($pasarPerKancab, $totalKgAll) {
            $row->total_pasar      = $pasarPerKancab[$row->kantor_cabang] ?? 0;
            $row->pct_pasar_aktif  = $row->total_pasar > 0
                ? round($row->pasar_aktif / $row->total_pasar * 100, 1)
                : 0;
            $row->avg_tx_per_pasar = $row->pasar_aktif > 0
                ? round($row->total_transaksi / $row->pasar_aktif)
                : 0;
            $row->kontribusi_pct   = round($row->total_kg / $totalKgAll * 100, 1);
            return $row;
        });

        // ── Volume harian per kancab — top 5 kancab (multi-line chart) ──────
        $top5Kancab = $kancabPerf->take(5)->pluck('kantor_cabang');

        $dailyKancab = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->whereBetween('transaksis.tanggal', [$dateFrom, $dateTo])
            ->whereIn('pasars.kantor_cabang', $top5Kancab)
            ->when($pasarId, fn($q) => $q->where('transaksis.pasar_id', $pasarId))
            ->selectRaw('transaksis.tanggal, pasars.kantor_cabang, SUM(transaksis.jumlah_kg) AS total_kg, COUNT(*) AS tx')
            ->groupBy('transaksis.tanggal', 'pasars.kantor_cabang')
            ->orderBy('transaksis.tanggal')
            ->get()
            ->groupBy('kantor_cabang');

        // ── Volume harian total ─────────────────────────────────────────────
        $dailyTotal = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->whereBetween('transaksis.tanggal', [$dateFrom, $dateTo])
            ->when($pasarId, fn($q) => $q->where('transaksis.pasar_id', $pasarId))
            ->when($kancab,  fn($q) => $q->where('pasars.kantor_cabang', $kancab))
            ->selectRaw('transaksis.tanggal, SUM(transaksis.jumlah_kg) AS total_kg, COUNT(*) AS tx')
            ->groupBy('transaksis.tanggal')
            ->orderBy('transaksis.tanggal')
            ->get();

        // ── Top 10 pasar by volume ──────────────────────────────────────────
        $topPasars = DB::table('transaksis')
            ->join('pasars', 'transaksis.pasar_id', '=', 'pasars.id')
            ->whereBetween('transaksis.tanggal', [$dateFrom, $dateTo])
            ->when($kancab,  fn($q) => $q->where('pasars.kantor_cabang', $kancab))
            ->when($pasarId, fn($q) => $q->where('transaksis.pasar_id', $pasarId))
            ->selectRaw('
                pasars.nama_pasar,
                pasars.kantor_cabang,
                pasars.kabupaten,
                SUM(transaksis.jumlah_kg)   AS total_kg,
                COUNT(transaksis.id)         AS total_transaksi,
                AVG(transaksis.jumlah_kg)   AS avg_kg
            ')
            ->groupBy('pasars.nama_pasar', 'pasars.kantor_cabang', 'pasars.kabupaten')
            ->orderByDesc('total_kg')
            ->limit(10)
            ->get();

        // ── Table: paginated transaksi ──────────────────────────────────────
        $transaksis = Transaksi::with('pasar:id,nama_pasar,kantor_cabang')
            ->whereBetween('tanggal', [$dateFrom, $dateTo])
            ->when($pasarId, fn($q) => $q->where('pasar_id', $pasarId))
            ->when($kancab,  fn($q) => $q->whereHas('pasar', fn($q2) => $q2->where('kantor_cabang', $kancab)))
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // ── Dropdown data ───────────────────────────────────────────────────
        $pasarList  = Pasar::orderBy('nama_pasar')->get(['id', 'nama_pasar', 'kantor_cabang']);
        $kancabList = Pasar::kancabOptions();

        return view('transaksis.index', compact(
            'transaksis',
            'kpi',
            'kancabPerf',
            'dailyKancab',
            'dailyTotal',
            'topPasars',
            'pasarList',
            'kancabList',
            'dateFrom',
            'dateTo',
            'pasarId',
            'kancab'
        ));
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pasar');
        return view('transaksis.show', compact('transaksi'));
    }

    public function create()
    {
        $pasarList = Pasar::orderBy('nama_pasar')->get(['id', 'nama_pasar', 'kantor_cabang']);
        return view('transaksis.create', compact('pasarList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pasar_id'   => ['required', 'exists:pasars,id'],
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'jumlah_kg'  => ['required', 'numeric', 'min:0.1', 'max:999'],
            'harga_jual' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        Transaksi::create($validated);

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaksi $transaksi)
    {
        $pasarList = Pasar::orderBy('nama_pasar')->get(['id', 'nama_pasar', 'kantor_cabang']);
        return view('transaksis.edit', compact('transaksi', 'pasarList'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'pasar_id'   => ['required', 'exists:pasars,id'],
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'jumlah_kg'  => ['required', 'numeric', 'min:0.1', 'max:999'],
            'harga_jual' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:500'],
        ]);

        $transaksi->update($validated);

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksis.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}