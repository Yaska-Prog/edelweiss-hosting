<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Http\Requests\StorePemesananRequest;
use App\Http\Requests\UpdatePemesananRequest;
use App\Models\Gaun;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pemesanans = Pemesanan::with('gaun')->get();
        $gauns = Gaun::all();
        $formattedPemesanans = $this->formatTanggalPemesanan($pemesanans);
        return view('pages.pemesanan', ['pemesanans' => $formattedPemesanans, 'gauns' => $gauns]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gauns = Gaun::all();
        return view('pages.tambah_data_pemesanan', compact('gauns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePemesananRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pemesanans = [];
        foreach ($request['gaun_kode'] as $index => $gaun) {
            $checkGaun = Pemesanan::where('gaun_kode', $gaun)->where('tanggal_sewa', $request->tanggal_sewa)->exists();
            if ($checkGaun) {
                return redirect()->back()->with('fail', "Gagal mendaftarkan pesanan, gaun dengan kode $gaun->kode telah disewakan pada tanggal tersebut");
            }
            $pemesanan = [
                'no_nota' => $request->no_nota,
                'tanggal_sewa' => $request->tanggal_sewa,
                'nama_penyewa' => $request->nama_penyewa,
                'nomor_hp' => $request->nomor_hp,
                'tanggal_ambil' => $request->tanggal_ambil,
                'tanggal_kembali' => $request->tanggal_kembali,
                'gaun_kode' => $gaun,
                'harga' => $request['harga'][$index],
                'dp' => $request['dp'][$index],
                'sisa' => $request['harga'][$index] - $request['dp'][$index],
                'note' => $request['note'][$index],
                'deposit_gaun' => $request['deposit'][$index],
                'tanggal_pembayaran' => $request->tanggal_pembayaran,
                'via_bayar' => $request->via_bayar,
                'atas_nama' => $request->atas_nama,
                'nama_sales' => $request['nama_sales'][$index],
            ];
            if ($request['is_paid'][$index]) $pemesanan['deposit'] = $request['deposit'][$index];
            else $pemesanan['deposit_pengambilan'] = $request['deposit'][$index];
            $pemesanans[] = $pemesanan;
        }
        Pemesanan::insert($pemesanans);
        return redirect()->back()->with('success', 'Sukses mendaftarkan data pemesanan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function show(Pemesanan $pemesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pemesanan $pemesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePemesananRequest  $request
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePemesananRequest $request, Pemesanan $pemesanan)
    {
        // dd($pemesanan);
        $pemesanan = $this->pemesananAvailability($request->gaun_kode, $request->tanggal_ambil, $request->tanggal_kembali);
        $checkGaun = $pemesanan->exists();
        if ($checkGaun) {
            return redirect()->back()->with('fail', "Gagal mendaftarkan pesanan, gaun dengan kode $request->kode telah disewakan pada tanggal tersebut");
        }
        Pemesanan::where('id', $pemesanan->id)->update([
            'no_nota' => $request->no_nota,
            'gaun_kode' => $request->gaun_kode,
            'tanggal_sewa' => $request->tanggal_sewa,
            'tanggal_ambil' => $request->tanggal_ambil,
            'tanggal_kembali' => $request->tanggal_kembali,
            'nama_penyewa' => $request->nama_penyewa,
            'nomor_hp' => $request->nomor_hp,
            'harga' => $request->harga,
            'dp' => $request->dp,
            'sisa' => $request->harga - $request->dp,
            'deposit' => $request->deposit,
            'note' => $request->note,
            'tanggal_di_ambil' => $request->tanggal_di_ambil,
            'kembali' => $request->kembali,
            'nomor_rekening' => $request->nomor_rekening,
            'jenis_bank' => $request->jenis_bank,
            'atas_nama_2' => $request->atas_nama_2,
            'tanggal_pengembalian_deposit' => $request->tanggal_pengembalian_deposit,
            'deposit_pengambilan' => $request->deposit_pengambilan,
            'deposit_gaun' => $request->deposit_gaun,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'via_bayar' => $request->via_bayar,
            'atas_nama' => $request->atas_nama,
            'tanggal_pembayaran_2' => $request->tanggal_pembayaran_2,
            'via_bayar_2' => $request->via_bayar_2,
            'atas_nama_22' => $request->atas_nama_22,
            'nama_sales' => $request->nama_sales,
        ]);
        return redirect('pemesanan')->with('success', 'Sukses mengupdate data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemesanan  $pemesanan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemesanan $pemesanan)
    {
        //
    }

    public function availabilityScreen()
    {
        $gauns = Gaun::all();
        return view('pages.availability', compact('gauns'));
    }

    public function checkAvailability(Request $request)
    {
        $pemesanan = Pemesanan::orderBy('id', 'DESC');
        $tanggalAkhir = $request->tanggal_akhir;
        $tanggalAwal = $request->tanggal_awal;
        if ($tanggalAkhir == null) {
            $tanggalAkhir = now();
        }
        $pemesanan = $this->pemesananAvailability($request->gaun, $tanggalAwal, $tanggalAkhir);
        // if ($request->gaun != null) $pemesanan->where('gaun_kode', $request->gaun);
        // if ($request->tanggal_awal != null) {
        //     $pemesanan->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
        //         $query->whereBetween('tanggal_ambil', [$tanggalAwal, $tanggalAkhir])
        //             ->orWhereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir])
        //             ->orWhere(function ($subQuery) use ($tanggalAwal, $tanggalAkhir) {
        //                 $subQuery->where('tanggal_ambil', '<=', $tanggalAwal)
        //                     ->where('tanggal_kembali', '>=', $tanggalAkhir);
        //             });
        //     });
        // }
        $pemesanans = $pemesanan->get();
        if (count($pemesanans) >= 1) {
            $formattedPemesanans = $this->formatPemesananNota($this->formatTanggalPemesanan($pemesanans));
            return response()->json(['is_available' => false, 'data' => $formattedPemesanans]);
        } else {
            $formattedPemesanans = $this->formatTanggalPemesanan(Pemesanan::all());
            return response()->json(['is_available' => true, 'data' => $formattedPemesanans]);
        }
    }

    public function pemesananAvailability($kodeGaun, $tanggalAwal, $tanggalAkhir){
        $pemesanan = Pemesanan::orderBy('id', 'DESC');
        if ($kodeGaun != null) $pemesanan->where('gaun_kode', $kodeGaun);
        if ($tanggalAwal != null) {
            $pemesanan->where(function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal_ambil', [$tanggalAwal, $tanggalAkhir])
                    ->orWhereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir])
                    ->orWhere(function ($subQuery) use ($tanggalAwal, $tanggalAkhir) {
                        $subQuery->where('tanggal_ambil', '<=', $tanggalAwal)
                            ->where('tanggal_kembali', '>=', $tanggalAkhir);
                    });
            });
        }
        return $pemesanan;
    }

    public function formatTanggalPemesanan($pemesanans)
    {
        $formattedPemesanans = $pemesanans->map(function ($pemesanan) {
            $pemesanan->tanggal_sewa = Carbon::parse($pemesanan->tanggal_sewa)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_sewa)->format('d-m-Y');
            $pemesanan->tanggal_ambil = $pemesanan->tanggal_ambil ? (Carbon::parse($pemesanan->tanggal_ambil)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_ambil)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->tanggal_kembali = $pemesanan->tanggal_kembali ? (Carbon::parse($pemesanan->tanggal_kembali)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_kembali)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->tanggal_di_ambil = $pemesanan->tanggal_di_ambil ? (Carbon::parse($pemesanan->tanggal_di_ambil)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_di_ambil)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->kembali = $pemesanan->kembali ? (Carbon::parse($pemesanan->kembali)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->kembali)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->tanggal_pengembalian_deposit = $pemesanan->tanggal_pengembalian_deposit ? (Carbon::parse($pemesanan->tanggal_pengembalian_deposit)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_pengembalian_deposit)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->tanggal_pembayaran = $pemesanan->tanggal_pembayaran ? (Carbon::parse($pemesanan->tanggal_pembayaran)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_pembayaran)->format('d-m-Y')) : 'belum di isikan';
            $pemesanan->tanggal_pembayaran_2 = $pemesanan->tanggal_pembayaran_2 ? (Carbon::parse($pemesanan->tanggal_pembayaran_2)->format('d-m-Y') == '01-01-1970' ? 'belum di isikan' : Carbon::parse($pemesanan->tanggal_pembayaran_2)->format('d-m-Y')) : 'belum di isikan';
            return $pemesanan;
        });

        return $formattedPemesanans;
    }

    public function formatPemesananNota($pemesanans)
    {
        $formattedPemesanans = [];
        foreach ($pemesanans as $key => $value) {
            $pemesanan = [];
            $pemesanan['no_nota'] = $value->no_nota;
            $pemesanan['gaun_kode'] = $value->gaun_kode;
            $pemesanan['tanggal_sewa'] = $value->tanggal_sewa;
            $pemesanan['tanggal_ambil'] = $value->tanggal_ambil;
            $pemesanan['tanggal_kembali'] = $value->tanggal_kembali;
            $pemesanan['nama_penyewa'] = $value->nama_penyewa;
            $pemesanan['nomor_hp'] = $value->nomor_hp;
            $pemesanan['harga'] = $value->harga;
            $pemesanan['dp'] = $value->dp;
            $pemesanan['sisa'] = $value->sisa;
            $pemesanan['deposit'] = $value->deposit;
            $pemesanan['nama_sales'] = $value->nama_sales;
            $formattedPemesanans[] = $pemesanan;
        }
        return $formattedPemesanans;
    }

    public function getDetailNota($kodeNota)
    {
        $nota = Pemesanan::where('no_nota', $kodeNota)->get();
        $formattedNota = $this->formatPemesananNota($nota);
        $gauns = [];
        foreach ($nota as $key => $value) {
            $gaun = [];
            $gaun['kode'] = $value->gaun_kode;
            $gaun['harga'] = $value->harga;
            $gaun['dp'] = $value->dp;
            $gaun['note'] = $value->note;
            $gauns[] = $gaun;
        }
        return response()->json(['success' => true, 'nota' => $formattedNota, 'gauns' => $gauns]);
    }
}
