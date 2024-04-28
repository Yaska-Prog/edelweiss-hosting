@extends('layouts.main')

@section('header')
    <header class="app-header">
        <nav class="navbar navbar-expand-lg">
            <h1 class="title">Check Availability</h1>
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
            {{-- please add the input here --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="mb-1" for="gaun-kode">Kode Gaun</label> <br>
                    <select id="select-gaun" class="form-select gaun-select" name="gaun_kode" required>
                        @foreach ($gauns as $gaun)
                            <option value="{{ $gaun->kode }}" data-image="{{ asset('storage/' . $gaun->gambar) }}">
                                {{ $gaun->kode }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mb-1" for="gaun-kode">Tanggal awal filter</label> <br>
                    <input type="date" class="form-control" id="tanggal-awal" placeholder="Start Date">
                </div>
                <div class="col-md-3">
                    <label class="mb-1" for="gaun-kode">Tanggal akhir filter</label> <br>
                    <input type="date" class="form-control" id="tanggal-akhir" placeholder="End Date">
                </div>
                <br>
                <button class="btn btn-success" id="check-availability">Check Availability</button>
            </div>
            {{-- Modify this --}}
            <h5 id="availability-status"></h5>
            <table class="table display nowrap" id="pemesanan-table-availability" style="width: 80%;">
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
                        {{-- <th>Tanggal Diambil</th>
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
                        <th>Atas nama (22)</th> --}}
                        <th>Nama Sales</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="gambar-gaun-modal" tabindex="-1" role="dialog" aria-labelledby="modalDashboardTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Gaun</h5>
                </div>
                <img src="" alt="gambar-gaun" id="detail-gambar-gaun-availability">
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.gaun-select').select2({
                templateResult: formatOption,
                templateSelection: formatSelection
            });

            function formatCurrency(amount) {
                return 'Rp. ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            var table = $('#pemesanan-table-availability').DataTable({
                scrollX: true,
                columns: [{
                        data: 'no_nota'
                    },
                    {
                        data: 'gaun_kode'
                    },
                    {
                        data: 'tanggal_sewa'
                    },
                    {
                        data: 'tanggal_ambil'
                    },
                    {
                        data: 'tanggal_kembali'
                    },
                    {
                        data: 'nama_penyewa'
                    },
                    {
                        data: 'nomor_hp'
                    },
                    {
                        data: 'harga'
                    },
                    {
                        data: 'dp'
                    },
                    {
                        data: 'sisa'
                    },
                    {
                        data: 'deposit'
                    },
                    {
                        data: 'nama_sales'
                    }
                ],
            });

            $('#check-availability').click(function() {
                var gaun = $('#select-gaun').val();
                var tanggalAwal = $('#tanggal-awal').val();
                var tanggalAkhir = $('#tanggal-akhir').val();
                console.log(gaun);
                console.log(tanggalAkhir);

                $('body').addClass('loading');

                $.ajax({
                    url: '{{ route('check-availability') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        gaun: gaun,
                        tanggal_awal: tanggalAwal,
                        tanggal_akhir: tanggalAkhir
                    },
                    success: function(response) {
                        // Hide loading indicator
                        $('body').removeClass('loading');

                        // Clear table body
                        table.clear().draw();
                        if (response.is_available) {
                            // Gaun is available
                            $('#availability-status').html(
                                '<h5 style="color: green;">Available</h5>');
                        } else {
                            // Gaun is not available
                            $('#availability-status').html(
                                '<h5 style="color: red;">Not Available</h5>');
                            var formattedData = response.data.map(function(row) {
                                // Format integer attributes into currency
                                console.log(row);
                                row.harga = row.harga === null ? 'Lunas' : formatCurrency(row.harga);
                                row.dp = row.dp === null ? 'Lunas' : formatCurrency(row.dp);
                                row.sisa = row.sisa === null ? 'Lunas' : formatCurrency(row.sisa);
                                row.deposit = row.deposit === null ? 'Lunas' : formatCurrency(row.deposit);
                                return row;
                            });
                            // Populate table with unavailable data
                            table.rows.add(formattedData).draw();
                        }
                    }
                });
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

            var baseUrl = "{{ asset('storage/') }}";
            $(document).on('click', '.detail-gambar-btn', function() {
                var gambar = $(this).data('gambar');
                $('#detail-gambar-gaun-availability').attr("src", baseUrl + '/' + gambar)
            });
        });
    </script>
@endsection
