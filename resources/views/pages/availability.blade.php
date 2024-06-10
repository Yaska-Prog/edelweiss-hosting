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
    <h5 id="availability-status"></h5>
    <div id="availability-details" style="display: none;">
        <div class="row align-items-center">
            <div class="col-md-6">
                <table class="table" style="width: 100%;">
                    <tr>
                        <th>Kode</th>
                        <td id="gaun-kode"></td>
                    </tr>
                    <tr>
                        <th>Warna</th>
                        <td id="gaun-warna"></td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td id="gaun-harga"></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <img src="" alt="gambar-gaun" id="detail-gambar-gaun-availability"
                    style="max-width: 100%; max-height: 300px;">
            </div>
        </div>
        <table class="table display nowrap" id="pemesanan-table-availability" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nomor Nota</th>
                    <th>Nama Penyewa</th>
                    <th>Nomor HP</th>
                    <th>Tanggal Ambil</th>
                    <th>Tanggal Kembali</th>
                </tr>
            </thead>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            const gauns = @json($gauns);
            $('.gaun-select').select2({
                templateResult: formatOption,
                templateSelection: formatSelection
            });

            function formatCurrency(amount) {
                return 'Rp. ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            $('#check-availability').click(function() {
                var gaun = $('#select-gaun').val();
                var tanggalAwal = $('#tanggal-awal').val();
                var tanggalAkhir = $('#tanggal-akhir').val();

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
                        $('body').removeClass('loading');

                        $('#availability-details').hide();
                        table.clear().draw();

                        var selectedGaun = gauns.find(g => g.kode === gaun);
                        showGaunDetails(selectedGaun);

                        if (response.is_available) {
                            $('#availability-status').html(
                                '<h5 style="color: green;">Available</h5>');
                        } else {
                            $('#availability-status').html(
                                '<h5 style="color: red;">Not Available</h5>');
                            var formattedData = response.data.map(function(row) {
                                return [
                                    row.no_nota,
                                    row.nama_penyewa,
                                    row.nomor_hp,
                                    row.tanggal_ambil,
                                    row.tanggal_kembali
                                ];
                            });
                            table.rows.add(formattedData).draw();
                        }
                    }
                });
            });

            function showGaunDetails(gaun) {
                $('#gaun-kode').text(gaun.kode);
                $('#gaun-warna').text(gaun.warna);
                $('#gaun-harga').text(formatCurrency(gaun.harga));
                $('#detail-gambar-gaun-availability').attr("src", "{{ asset('storage/') }}/" + gaun.gambar);
                $('#availability-details').show();
            }

            var table = $('#pemesanan-table-availability').DataTable({
                scrollX: true,
                columns: [{
                        data: 0
                    },
                    {
                        data: 1
                    },
                    {
                        data: 2
                    },
                    {
                        data: 3
                    },
                    {
                        data: 4
                    }
                ]
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
