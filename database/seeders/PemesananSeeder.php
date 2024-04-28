<?php

namespace Database\Seeders;

use App\Imports\PemesananImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class PemesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new PemesananImport, storage_path('app/public/Pemesanan.xlsx'));
    }
}
