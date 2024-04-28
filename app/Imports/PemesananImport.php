<?php

namespace App\Imports;

use App\Models\Gaun;
use App\Models\Pemesanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PemesananImport implements ToModel, WithStartRow, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $formatDate = function($value) {
            return is_numeric($value) ? date('d/m/Y', Date::excelToTimestamp($value)) : '01/01/1970';
        };
        if ($row['no_nota'] == null) return;
        if (!Gaun::where('kode', $row['kode'])->exists()) {
            Gaun::create([
                'kode' => $row['kode'],
                'gambar' => 'default.png',
                'warna' => 'Default',
                'harga' => 1200000
            ]);
        }
        // dd($row);
        $sisa = $row['harga'] - $row['dp'];
        return new Pemesanan([
            'no_nota' => $row['no_nota'],
            'gaun_kode' => $row['kode'],
            'tanggal_sewa' => $formatDate($row['tgl_sewa']),
            'tanggal_ambil' => $formatDate($row['tgl_ambil']),
            'tanggal_kembali' => $formatDate($row['tgl_kembali']),
            'nama_penyewa' => $row['nama'],
            'nomor_hp' => $row['no_hp'],
            'harga' => $row['harga'],
            'dp' => $row['dp'],
            'sisa' => $sisa,
            'deposit' => $row['deposit'],
            'note' => $row['note'],
            'tanggal_di_ambil' => $formatDate($row['di_ambil']),
            'kembali' => $formatDate($row['kembali']),
            'nomor_rekening' => $row['no_rek'],
            'jenis_bank' => $row['bank'],
            'atas_nama_2' => $row['an_2'],
            'tanggal_pengembalian_deposit' => $formatDate($row['pengembalian_deposit']),
            'deposit_pengambilan' => $row['deposit_gaun'], //rumus
            'deposit_gaun' => $row['deposit_gaun'],
            'tanggal_pembayaran' => $formatDate($row['pembayaran_1']),
            'via_bayar' => $row['via'],
            'atas_nama' => $row['an'],
            'tanggal_pembayaran_2' => $formatDate($row['pembayaran_2']),
            'via_bayar_2' => $row['via_2'],
            'atas_nama_22' => $row['an_22'],
            'nama_sales' => $row['sales'],
            // 'status' => 'On-Going',
            // 'deposit_nota' => $row['deposit_nota']
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function headingRow(): int
    {
        return 1;
    }

    // public function columnFormats(): array
    // {
    //     return [
    //         'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'E' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'R' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'U' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //         'X' => NumberFormat::FORMAT_DATE_DDMMYYYY,
    //     ];
    // }
}
