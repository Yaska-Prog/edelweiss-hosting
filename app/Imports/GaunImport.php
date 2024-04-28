<?php

namespace App\Imports;

use App\Models\Gaun;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class GaunImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function startRow(): int
    {
        return 2;
    }
    
    public function model(array $row)
    {
        if($row[1] == null) return;
        return new Gaun([
            'kode' => $row[1],
            'gambar' => 'default.png',
            'warna' => $row[3],
            'harga' => $row[4],
        ]);
    }
}
