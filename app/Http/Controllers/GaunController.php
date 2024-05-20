<?php

namespace App\Http\Controllers;

use App\Models\Gaun;
use App\Http\Requests\StoreGaunRequest;
use App\Http\Requests\UpdateGaunRequest;
use App\Imports\PemesananImport;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GaunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gauns = Gaun::OrderBy('kode', 'asc')->get();
        return view('pages.dashboard', compact('gauns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.tambah_data_gaun');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGaunRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGaunRequest $request)
    {
        if ($request->hasFile('gambar')) {
            $imgFile = $request->file('gambar');
            $gambar = time() . '.' . $imgFile->getClientOriginalExtension();
            Storage::disk('public')->put($gambar, file_get_contents($imgFile));
            Gaun::create([
                'kode' => $request->kode,
                'gambar' => $gambar,
                'warna' => $request->warna,
                'harga' => $request->harga,
                'usia' => $request->usia,
            ]);
            return redirect('gaun/create')->with('success', 'Sukses mendaftarkan data gaun');
        } else {
            return redirect('gaun/create')->with('fail', 'Gagal mendaftarkan gaun, gambar tidak terdeteksi');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gaun  $gaun
     * @return \Illuminate\Http\Response
     */
    public function show(Gaun $gaun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gaun  $gaun
     * @return \Illuminate\Http\Response
     */
    public function edit(Gaun $gaun)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGaunRequest  $request
     * @param  \App\Models\Gaun  $gaun
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGaunRequest $request, Gaun $gaun)
    {
        //duplicate gaun code check if the user change the code.
        if ($gaun->kode != $request->kode && Gaun::where('kode', $request->kode)->exists()) return redirect('gaun')->with('fail', 'Gagal mengupdate data, kode sudah terdaftar');
        if ($request->hasFile('gambar')) {
            $imgFile = $request->file('gambar');
            $gambar = time() . '.' . $imgFile->getClientOriginalExtension();
            Storage::disk('public')->put($gambar, file_get_contents($imgFile));
            Gaun::where('kode', $gaun->kode)->update([
                'kode' => $request->kode,
                'gambar' => $gambar,
                'warna' => $request->warna,
                'harga' => $request->harga,
                'usia' => $request->usia,
            ]);
        } else {
            Gaun::where('kode', $gaun->kode)->update([
                'kode' => $request->kode,
                'warna' => $request->warna,
                'harga' => $request->harga,
                'usia' => $request->usia,
            ]);
        }

        return redirect('gaun')->with('success', 'Sukses mengupdate data gaun');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gaun  $gaun
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gaun $gaun, Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $pemesanan = Pemesanan::where('gaun_kode', $gaun->kode)->exists();
            if ($pemesanan) {
                return redirect('gaun')->with('fail', 'Gagal menghapus data gaun, terdapat pemesanan yang menggunakan gaun ini.');
            }
            Gaun::where('kode', $gaun->kode)->delete();
            return redirect('gaun')->with('success', 'Sukses menghapus data gaun');
        }
        return redirect('gaun')->with('fail', 'Gagal menghapus data gaun, password salah.');
    }

    public function uploadExcel(Request $request)
    {
        // Validate the uploaded file
        // $request->validate([
        //     'excel_file' => 'required|file|mimes:xls,xlsx',
        // ]);
        // Excel::import(new GaunImport, storage_path('app/public/Inventory.xlsx'));

        // Read the Excel file
        $excelData = Excel::import(new PemesananImport, storage_path('app/public/Pemesanan.xlsx'));
        // dd($excelData);
        // $inventoryData = $excelData[0];
        return response()->json(['success' => true, 'inventoryData' => 'Good']);
    }

    public function checkKode(Request $request)
    {
        return Gaun::where('kode', $request->kode)->exists();
    }

    public function getAllGaun(Request $request)
    {
        $gaun = Gaun::orderBy('kode', 'ASC');
        if ($request->kode != null) $gaun->where('kode', $request->kode);
        $gauns = $gaun->get();
        return response()->json(['success' => true, 'gauns' => $gauns]);
    }

    public function createGaun(Request $request)
    {
        $rules = [
            'kode' => 'required|unique:gauns',
            'gambar' => ['required', 'image', 'mimes:jpeg,png,gif,jpg'],
            'warna' => 'required',
            'harga' => 'required',
            'usia' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, $validator->errors()], 422);
        }
        $imgFile = $request->file('gambar');
        $gambar = time() . '.' . $imgFile->getClientOriginalExtension();
        Storage::disk('public')->put($gambar, file_get_contents($imgFile));
        Gaun::create([
            'kode' => $request->kode,
            'gambar' => $gambar,
            'warna' => $request->warna,
            'harga' => (int)$request->harga,
            'usia' => $request->usia,
        ]);
        return response()->json(['success' => true, 'message' => 'Berhasil membuat gaun baru.']);
    }

    public function deleteGaun($gaunKode)
    {
        Gaun::where('kode', $gaunKode)->delete();
        return response()->json(['success' => true, 'message' => 'Berhasil menghapus gaun.']);
    }

    public function updateGaun(Request $request, $kodeGaun)
    {
        $rules = [
            'kode' => 'required',
            'gambar' => ['image', 'mimes:jpeg,png,gif,jpg'],
            'warna' => 'required',
            'harga' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, $validator->errors(), 422]);
        }
        if ($kodeGaun != $request->kode && Gaun::where('kode', $request->kode)->exists()) return response()->json(['success' => false, 'message' => 'Kode gaun sudah ada, harap mengganti dengan kode yang berbeda']);
        if ($request->hasFile('gambar')) {
            $imgFile = $request->file('gambar');
            $gambar = time() . '.' . $imgFile->getClientOriginalExtension();
            Storage::disk('public')->put($gambar, file_get_contents($imgFile));
            Gaun::where('kode', $kodeGaun)->update([
                'kode' => $request->kode,
                'gambar' => $gambar,
                'warna' => $request->warna,
                'harga' => (int)$request->harga,
                'usia' => $request->usia,
            ]);
        } else {
            Gaun::where('kode', $kodeGaun)->update([
                'kode' => $request->kode,
                'warna' => $request->warna,
                'harga' => (int)$request->harga,
                'usia' => $request->usia,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Berhasil memperbarui gaun.']);
    }
}
