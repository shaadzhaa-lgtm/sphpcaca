<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PasarImport;

class PasarController extends Controller
{
    /**
     * Menampilkan Dashboard Utama dengan Data Penyaluran
     */
    public function index()
    {
        // 1. Ambil data pasar + Hitung total kg penyaluran per pasar (untuk di sidebar)
        // Eager Load 'transaksis' untuk detail modal agar tidak error/lambat
        $semuaPasar = Pasar::withSum('transaksis', 'jumlah_kg')
                           ->with('transaksis') 
                           ->get();
        
        // 2. Statistik Header
        $jumlahPasar = $semuaPasar->count();
        $jumlahKabupaten = Pasar::distinct('kabupaten')->count('kabupaten');
        $jumlahCabang = Pasar::distinct('kantor_cabang')->count('kantor_cabang');
        
        // 3. Statistik Penyaluran
        $pasarPantauan = 42; 
        $totalSphp = Transaksi::sum('jumlah_kg');

        // 4. Data Visualisasi (Grafik)
        $listCabang = ['BOGOR', 'CIANJUR', 'BANDUNG', 'CIAMIS', 'CIREBON', 'INDRAMAYU', 'SUBANG', 'KARAWANG'];
        $dataDonut = [15, 12, 18, 10, 20, 25, 14, 11];
        $dataBar = [25, 14, 14, 13, 11];

        // 5. Kirim data ke View
        return view('beranda', compact(
            'semuaPasar', 
            'jumlahPasar', 
            'jumlahKabupaten', 
            'jumlahCabang', 
            'listCabang', 
            'dataDonut', 
            'dataBar',
            'pasarPantauan',
            'totalSphp'
        ));
    }

    /**
     * Menampilkan Halaman Upload Excel
     */
    public function halamanUpload()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/beranda');
        }
        return view('upload_pasar');
    }

    /**
     * Proses Import Data Pasar dari Excel
     */
    public function prosesUpload(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new PasarImport, $request->file('file_excel'));
        
        return redirect()->route('beranda')->with('success', 'Data Pasar & Lokasi Berhasil Diupdate!');
    }
}