@extends('layouts.main')

@section('header')
    <header class="app-header">
        <nav class="navbar navbar-expand-lg">
            <h1 class="title">Tambah Data Pemesanan</h1>
        </nav>
    </header>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('fail'))
        <div class="alert alert-danger">
            {{ session('fail') }}
        </div>
    @endif
    
    <div class="row">
        <div class="card">
            <div class="card-body">
                <form id="edit-pemesanan-form" method="POST" action="{{ route('pemesanan.store') }}">
                    @csrf
                    <div class="form-group mb-2">
                        <label class="mb-1" for="no-nota">Nomor Nota</label>
                        <input type="text" class="form-control" id="no-nota" name="no_nota" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="tanggal-sewa">Tanggal Sewa</label>
                        <input type="date" class="form-control" id="tanggal-sewa" name="tanggal_sewa" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="nama-penyewa">Nama Penyewa</label>
                        <input type="text" class="form-control" id="nama-penyewa" name="nama_penyewa" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="nomor-hp">Nomor HP</label>
                        <input type="text" class="form-control" id="nomor-hp" name="nomor_hp" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="tanggal-ambil">Tanggal Ambil</label>
                        <input type="date" class="form-control" id="tanggal-ambil" name="tanggal_ambil">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="tanggal-kembali">Tanggal Kembali</label>
                        <input type="date" class="form-control" id="tanggal-kembali" name="tanggal_kembali">
                    </div>
                    <br>
                    {{-- Gaun Section starts here --}}
                    <div id="gaun-container">
                        <h4>Gaun 1</h4>
                        <div class="form-group mb-2 gaun-input">
                            <label class="mb-1" for="gaun-kode">Kode Gaun</label> <br>
                            <select class="form-select gaun-select" name="gaun_kode[]" required>
                                @foreach ($gauns as $gaun)
                                    <option value="{{ $gaun->kode }}"
                                        data-image="{{ asset('storage/' . $gaun->gambar) }}">
                                        {{ $gaun->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="harga">Harga</label>
                            <input type="number" class="form-control harga-input" name="harga[]" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="deposit">Deposit</label>
                            <input type="number" class="form-control deposit-input" name="deposit[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="dp">dp</label>
                            <input type="number" class="form-control dp-input" name="dp[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="note">note</label>
                            <input type="text" class="form-control note-input" name="note[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="is_paid">Deposit Langsung Dibayarkan?</label>
                            <select class="form-select" name="is_paid[]" required>
                                <option value="1">Iya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <button type="button" id="add-gaun" class="btn btn-success w-100 py-8 fs-4 mb-4 rounded-2">Tambah Gaun</button>
                    {{-- Gaun Section ends here --}}
                    <div class="form-group mb-2">
                        <label class="mb-1" for="sisa">Sisa</label>
                        <input type="number" class="form-control" id="sisa" name="sisa" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="kembali">Kembali</label>
                        <input type="date" class="form-control" id="kembali" name="kembali">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="nomor-rekening">Nomor Rekening</label>
                        <input type="text" class="form-control" id="nomor-rekening" name="nomor_rekening">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="jenis-bank">Jenis Bank</label>
                        <input type="text" class="form-control" id="jenis-bank" name="jenis_bank">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="atas-nama-2">Atas Nama (2)</label>
                        <input type="text" class="form-control" id="atas-nama-2" name="atas_nama_2">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="tanggal-pembayaran">Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tanggal-pembayaran"
                            name="tanggal_pembayaran">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="via-bayar">Via Bayar</label>
                        <input type="text" class="form-control" id="via-bayar" name="via_bayar">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="atas-nama">Atas Nama</label>
                        <input type="text" class="form-control" id="atas-nama" name="atas_nama">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="tanggal-pembayaran-2">Tanggal Pembayaran 2</label>
                        <input type="date" class="form-control" id="tanggal-pembayaran-2"
                            name="tanggal_pembayaran_2">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="via-bayar-2">Via Bayar 2</label>
                        <input type="text" class="form-control" id="via-bayar-2" name="via_bayar_2">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="atas-nama-22">Atas Nama (22)</label>
                        <input type="text" class="form-control" id="atas-nama-22" name="atas_nama_22">
                    </div>
                    <div class="form-group mb-2">
                        <label class="mb-1" for="nama-sales">Nama Sales</label>
                        <input type="text" class="form-control" id="nama-sales" name="nama_sales">
                    </div>
                    <input type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize select2 for the first gaun input
            $('.gaun-select').select2({
                templateResult: formatOption,
                templateSelection: formatSelection
            });

            // Add event listener for the "Tambah Gaun" button
            $('#add-gaun').click(function() {
                var gaunCount = $('.gaun-input').length;
                if (gaunCount >= 3) {
                    alert('Maksimum gaun yang terdapat di nota adalah 3.');
                    return;
                }

                var newGaunInput = `
                    <div class="gaun-input">
                        <h4>Gaun ${gaunCount + 1}</h4>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="gaun-kode">Kode Gaun</label> <br>
                            <select class="form-select gaun-select" name="gaun_kode[]" required>
                                @foreach ($gauns as $gaun)
                                    <option value="{{ $gaun->kode }}" data-image="{{ asset('storage/' . $gaun->gambar) }}">
                                        {{ $gaun->kode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="harga">Harga</label>
                            <input type="number" class="form-control harga-input" name="harga[]" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="deposit">Deposit</label>
                            <input type="number" class="form-control deposit-input" name="deposit[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="dp">dp</label>
                            <input type="number" class="form-control dp-input" name="dp[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="note">note</label>
                            <input type="text" class="form-control note-input" name="note[]">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="is_paid">Deposit Langsung Dibayarkan?</label>
                            <select class="form-select" name="is_paid[]" required>
                                <option value="1">Iya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                `;

                $('#gaun-container').append(newGaunInput);
                $('.gaun-select').select2({
                    templateResult: formatOption,
                    templateSelection: formatSelection
                });

                // Hide the "Tambah Gaun" button if the maximum number of gauns is reached
                if (gaunCount === 2) {
                    $('#add-gaun').hide();
                }
            });

            function formatOption(option) {
                if (!option.id) {
                    return option.text;
                }

                var imageUrl = $(option.element).data('image');
                var $option = $(
                    '<div>' + option.text + '<br><img src="' + imageUrl +
                    '" style="max-width: 100px; max-height: 100px;" /></div>'
                );
                return $option;
            }

            function formatSelection(option) {
                return option.text;
            }

            // Hide image preview on select close
            $('.select2-dropdown').on('select2:close', function() {
                $('.select2-results__option--highlighted img').hide();
            });
        });
    </script>
@endsection
