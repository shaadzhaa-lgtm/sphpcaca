<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePasarTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all IDs from the pasar table
        $pasars = DB::table('pasars')->pluck('id');

        foreach ($pasars as $id) {
            DB::table('pasars')
                ->where('id', $id)
                ->update([
                    'target' => rand(70000, 90000)
                ]);
        }
    }
}