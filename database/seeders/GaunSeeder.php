<?php

namespace Database\Seeders;

use App\Imports\GaunImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GaunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new GaunImport, storage_path('app/public/Inventory.xlsx'));
    }
}
