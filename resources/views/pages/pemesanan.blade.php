@extends('layouts.main')

@section('header')
    <header class="app-header">
        <nav class="navbar navbar-expand-lg">
            <h1 class="title">Jadwal Pemesanan</h1>
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
    <div class="row">
        <div class="card">
            <table class="table display nowrap" id="pemesanan-table" style="width: 80%;">
                <thead>
                    <tr>
                        <th>Nomor Nota</th>
                        <th>Kode Gaun</th>
                        <th>Tanggal Sewa</th>
                        <th>Tanggal Ambil</th>
                        <th>Tanggal Kembali</th>
                        <th>Nama Penyewa</th>
                        <th>Nomor HP</th>
                        <th>Harga</th>
                        <th>DP</th>
                        <th>Sisa</th>
                        <th>Deposit</th>
                        <th>Tanggal Diambil</th>
                        <th>Kembali</th>
                        <th>Nomor Rekening</th>
                        <th>Jenis Bank</th>
                        <th>Atas Nama (2)</th>
                        <th>Tanggal Pengembalian Deposit</th>
                        <th>Deposit Pengambilan</th>
                        <th>Deposit Gaun</th>
                        <th>Tanggal Pembayaran</th>
                        <th>Via Bayar</th>
                        <th>Atas Nama</th>
                        <th>Tanggal Pembayaran 2</th>
                        <th>Via Bayar 2</th>
                        <th>Atas nama (22)</th>
                        <th>Nama Sales</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemesanans as $pemesanan)
                        <tr>
                            <td>{{ $pemesanan->no_nota }}</td>
                            @if ($pemesanan->gaun)
                                <td><a style="cursor: pointer;" class="detail-gambar-btn" data-toggle="modal"
                                        data-target="#gambar-gaun-modal"
                                        data-gambar="{{ $pemesanan->gaun->gambar }}">{{ $pemesanan->gaun->kode }}</a></td>
                            @else
                                <td>Gaun tidak ditemukan</td>
                            @endif
                            <td>{{ $pemesanan->tanggal_sewa }}
                            </td>
                            <td>{{ $pemesanan->tanggal_ambil }}
                            </td>
                            <td>{{ $pemesanan->tanggal_kembali }}
                            </td>
                            <td>{{ $pemesanan->nama_penyewa }}</td>
                            <td>{{ $pemesanan->nomor_hp }}</td>
                            <td class="currency-value">{{ $pemesanan->harga }}</td>
                            <td class="currency-value">{{ $pemesanan->dp }}</td>
                            <td class="currency-value">{{ $pemesanan->sisa == 0 ? 'Lunas' : $pemesanan->sisa }}</td>
                            <td class="currency-value">{{ $pemesanan->deposit }}</td>
                            <td>{{ $pemesanan->tanggal_di_ambil }}
                            </td>
                            <td>{{ $pemesanan->kembali }}
                            </td>
                            <td>{{ $pemesanan->nomor_rekening }}</td>
                            <td>{{ $pemesanan->jenis_bank }}</td>
                            <td>{{ $pemesanan->atas_nama_2 }}</td>
                            <td>{{ $pemesanan->tanggal_pengembalian_deposit }}
                            </td>
                            <td class="currency-value">{{ $pemesanan->deposit_pengambilan }}</td>
                            <td class="currency-value">{{ $pemesanan->deposit_gaun }}</td>
                            <td>{{ $pemesanan->tanggal_pembayaran }}
                            </td>
                            <td>{{ $pemesanan->via_bayar }}</td>
                            <td>{{ $pemesanan->atas_nama }}</td>
                            <td>{{ $pemesanan->tanggal_pembayaran_2 }}
                            </td>
                            <td>{{ $pemesanan->via_bayar_2 }}</td>
                            <td>{{ $pemesanan->atas_nama_22 }}</td>
                            <td>{{ $pemesanan->nama_sales }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-edit-pemesanan" data-toggle="modal"
                                    data-target="#edit-pemesanan-modal" data-pemesanan-id="{{ $pemesanan->id }}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bootstrap Modal --}}
    <div class="modal fade" id="edit-pemesanan-modal" tabindex="-1" role="dialog"
        aria-labelledby="editPemesananModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPemesananModalLabel">Edit Pemesanan</h5>
                </div>
                <form id="edit-pemesanan-form" method="POST" action="{{ route('pemesanan.update', 'PLACEHOLDER') }}">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label class="mb-1" for="no-nota">Nomor Nota</label>
                            <input type="text" class="form-control" id="no-nota" name="no_nota" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="gaun-kode">Kode Gaun</label>
                            <select class="form-select" name="gaun_kode" id="gaun-kode" required>
                                @foreach ($gauns as $gaun)
                                    <option value="{{ $gaun->kode }}">{{ $gaun->kode }} </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="tanggal-sewa">Tanggal Sewa</label>
                            <input type="datetime-local" class="form-control" id="tanggal-sewa" name="tanggal_sewa"
                                required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="tanggal-ambil">Tanggal Ambil</label>
                            <input type="datetime-local" class="form-control" id="tanggal-ambil" name="tanggal_ambil">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="tanggal-kembali">Tanggal Kembali</label>
                            <input type="datetime-local" class="form-control" id="tanggal-kembali" name="tanggal_kembali">
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
                            <label class="mb-1" for="harga">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="dp">DP</label>
                            <input type="number" class="form-control" id="dp" name="dp" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="sisa">Sisa</label>
                            <input type="number" class="form-control" id="sisa" name="sisa" required>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="deposit">Deposit</label>
                            <input type="number" class="form-control" id="deposit" name="deposit">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="note">Note</label>
                            <input type="text" class="form-control" id="note" name="note"></input>
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="tanggal-di-ambil">Tanggal Diambil</label>
                            <input type="datetime-local" class="form-control" id="tanggal-di-ambil"
                                name="tanggal_di_ambil">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="kembali">Kembali</label>
                            <input type="datetime-local" class="form-control" id="kembali" name="kembali">
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
                            <label class="mb-1" for="tanggal-pengembalian-deposit">Tanggal Pengembalian Deposit</label>
                            <input type="datetime-local" class="form-control" id="tanggal-pengembalian-deposit"
                                name="tanggal_pengembalian_deposit">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="deposit-pengambilan">Deposit Pengambilan</label>
                            <input type="number" class="form-control" id="deposit-pengambilan"
                                name="deposit_pengambilan">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="deposit-gaun">Deposit Gaun</label>
                            <input type="number" class="form-control" id="deposit-gaun" name="deposit_gaun">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="tanggal-pembayaran">Tanggal Pembayaran</label>
                            <input type="datetime-local" class="form-control" id="tanggal-pembayaran"
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
                            <input type="datetime-local" class="form-control" id="tanggal-pembayaran-2"
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gambar-gaun-modal" tabindex="-1" role="dialog" aria-labelledby="modalDashboardTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Gaun</h5>
                </div>
                <img src="" alt="gambar-gaun" id="detail-gambar-gaun">
            </div>
        </div>
    </div>

    <script>
        function formatCurrency(amount) {
            if (amount > 0) {
                return 'Rp. ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                return 0;
            }
        }
        $('.currency-value').each(function() {
            let amount = parseInt($(this).text());
            $(this).text(formatCurrency(amount));
        });
        let pemesanans = @json($pemesanans);
        var pemesananId;
        $('.btn-edit-pemesanan').click(function() {
            pemesananId = $(this).data('pemesanan-id');
            let selectedPemesanan = pemesanans.find(p => p.id == pemesananId);
            console.log(pemesananId);
            console.log(selectedPemesanan);
            if (selectedPemesanan) {
                // $.each(selectedPemesanan, function(key, value) {
                //     if (value === '1970-01-01 00:00:00') {
                //         // Set value to today's date
                //         selectedPemesanan[key] = new Date().toISOString().slice(0, 16);
                //         console.log(selectedPemesanan[key]);
                //     }
                // });
                $('#no-nota').val(selectedPemesanan.no_nota);
                $('#gaun-kode').val(selectedPemesanan.gaun.kode);
                $('#tanggal-sewa').val(selectedPemesanan.tanggal_sewa);
                $('#tanggal-ambil').val(selectedPemesanan.tanggal_ambil);
                $('#tanggal-kembali').val(selectedPemesanan.tanggal_kembali);
                $('#nama-penyewa').val(selectedPemesanan.nama_penyewa);
                $('#nomor-hp').val(selectedPemesanan.nomor_hp);
                $('#harga').val(selectedPemesanan.harga);
                $('#dp').val(selectedPemesanan.dp);
                $('#sisa').val(selectedPemesanan.sisa);
                $('#deposit').val(selectedPemesanan.deposit);
                $('#note').val(selectedPemesanan.note);
                $('#tanggal-di-ambil').val(selectedPemesanan.tanggal_di_ambil);
                $('#kembali').val(selectedPemesanan.kembali);
                $('#nomor-rekening').val(selectedPemesanan.nomor_rekening);
                $('#jenis-bank').val(selectedPemesanan.jenis_bank);
                $('#atas-nama-2').val(selectedPemesanan.atas_nama_2);
                $('#tanggal-pengembalian-deposit').val(selectedPemesanan.tanggal_pengembalian_deposit);
                $('#deposit-pengambilan').val(selectedPemesanan.deposit_pengambilan);
                $('#deposit-gaun').val(selectedPemesanan.deposit_gaun);
                $('#tanggal-pembayaran').val(selectedPemesanan.tanggal_pembayaran);
                $('#via-bayar').val(selectedPemesanan.via_bayar);
                $('#atas-nama').val(selectedPemesanan.atas_nama);
                $('#tanggal-pembayaran-2').val(selectedPemesanan.tanggal_pembayaran_2);
                $('#via-bayar-2').val(selectedPemesanan.via_bayar_2);
                $('#atas-nama-22').val(selectedPemesanan.atas_nama_22);
                $('#nama-sales').val(selectedPemesanan.nama_sales);
                $('#edit-pemesanan-form').attr('action', '/pemesanan/' + pemesananId);
                // $('#edit-pemesanan-modal').modal('show');
            }
        });
        var baseUrl = "{{ asset('storage/') }}";
        $(document).on('click', '.detail-gambar-btn', function() {
            var gambar = $(this).data('gambar');
            $('#detail-gambar-gaun').attr("src", baseUrl + '/' + gambar)
        });
        let table = new DataTable('#pemesanan-table', {
            scrollX: true,
            rowCallback: function(row, data) {
                // Get the values of tanggal_ambil and tanggal_kembali
                const tanggalAmbil = data[11];
                const tanggalKembali = data[12];

                // Check if both tanggal_ambil and tanggal_kembali are null or not
                if (tanggalAmbil != "Tanggal tidak terdaftar" && tanggalKembali !=
                    "Tanggal tidak terdaftar") {
                    // Highlight the row with green color
                    $(row).css('background-color', 'lightgreen');
                } else if (tanggalAmbil != "Tanggal tidak terdaftar" && tanggalKembali ==
                    "Tanggal tidak terdaftar") {
                    // Highlight the row with red color
                    $(row).css('background-color', 'yellow');
                } else {
                    // Do nothing, leave the row with the default white color
                    $(row).css('background-color', 'white');
                }
            }
        });
    </script>
@endsection
