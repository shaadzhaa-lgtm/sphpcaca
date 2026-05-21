<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransaksiBesarSeeder extends Seeder
{
    private const DATE_START  = '2025-04-01';
    private const DATE_END    = '2026-05-11';
    private const MIN_KG      = 300;
    private const MAX_KG      = 1000;
    private const KG_STEP     = 5;
    private const MIN_HARGA   = 60000;
    private const MAX_HARGA   = 62000;
    private const HARGA_STEP  = 500;
    private const CHUNK_SIZE  = 500;

    public function run(): void
    {
        // Mengambil ID dari tabel pasars
        $pasarIds = DB::table('pasars')->pluck('id')->toArray();

        if (empty($pasarIds)) {
            $this->command->error('Tabel pasars kosong. Jalankan PasarSeeder terlebih dahulu.');
            return;
        }

        $dates       = $this->buildDateArray(self::DATE_START, self::DATE_END);
        $kgValues    = range(self::MIN_KG, self::MAX_KG, self::KG_STEP);
        $hargaValues = range(self::MIN_HARGA, self::MAX_HARGA, self::HARGA_STEP);
        $kgCount     = count($kgValues)    - 1;
        $hargaCount  = count($hargaValues) - 1;

        $totalDays  = count($dates);
        $totalPasar = count($pasarIds);
        $totalRows  = $totalPasar * $totalDays;

        $this->command->info("┌─ TransaksiBesarSeeder ────────────────────────────");
        $this->command->info("│  Pasar      : {$totalPasar}");
        $this->command->info("│  Hari       : {$totalDays}");
        $this->command->info("│  Total      : " . number_format($totalRows) . " transaksi");
        $this->command->info("└───────────────────────────────────────────────────");

        $bar = $this->command->getOutput()->createProgressBar($totalPasar);
        $bar->start();

        $now   = now()->toDateTimeString();
        $chunk = [];

        // Supaya tidak error foreign key saat proses input massal
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($pasarIds as $pasarId) {
            foreach ($dates as $tanggal) {
                $chunk[] = [
                    'pasar_id'   => $pasarId,
                    'tanggal'    => $tanggal,
                    'jumlah_kg'  => $kgValues[rand(0, $kgCount)],
                    // DISESUAIKAN: Nama kolom di HeidiSQL adalah harga_jual
                    'harga_jual' => $hargaValues[rand(0, $hargaCount)], 
                    'keterangan' => $this->keterangan(),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($chunk) >= self::CHUNK_SIZE) {
                    DB::table('transaksis')->insert($chunk);
                    $chunk = [];
                }
            }
            $bar->advance();
        }

        if (!empty($chunk)) {
            DB::table('transaksis')->insert($chunk);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $bar->finish();
        $this->command->newLine(2);
    }

    private function buildDateArray(string $start, string $end): array
    {
        $dates   = [];
        $current = new \DateTime($start);
        $endDt   = new \DateTime($end);
        while ($current <= $endDt) {
            $dates[] = $current->format('Y-m-d');
            $current->modify('+1 day');
        }
        return $dates;
    }

    private function keterangan(): ?string
    {
        $pool = [null, 'Penjualan harian', 'Operasi pasar', 'Stok tambahan', 'Pasokan dari gudang'];
        return $pool[array_rand($pool)];
    }
}