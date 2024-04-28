@extends('layouts.main')

@section('header')
    <header class="app-header">
        <nav class="navbar navbar-expand-lg">
            <h1 class="title">Tambah Data Gaun</h1>
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
            {{-- Form --}}
            <form method="POST" action="{{ route('gaun.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-2">
                    <label class="mb-1" for="kode-gaun">Kode Gaun</label>
                    <input type="text" class="form-control" id="kode-gaun-input" name="kode" required>
                </div>
                <div class="form-group mb-2">
                    <label for="gambar-gaun-input">Gambar Gaun</label>
                    <div id="gaun-preview-box" class="border border-dashed p-3 mb-3">
                        <p class="text-muted mb-0">Drag and drop image here or click to upload</p>
                    </div>
                    <span id="selected-file-name"></span>
                    <input type="file" id="gambar-gaun-input" name="gambar" accept="image/*" style="display: none;"
                        required>
                </div>
                <div class="form-group mb-2">
                    <label class="mb-1" for="warna-gaun">Warna Gaun</label>
                    <input type="text" class="form-control" name="warna">
                </div>
                <div class="form-group mb-2">
                    <label class="mb-1" for="harga-gaun">Harga Gaun</label>
                    <input type="number" class="form-control" name="harga">
                </div>
                <div class="form-group mb-2">
                    <label class="mb-1" for="usia-gaun">Usia Pemakaian Gaun</label>
                    <select name="usia" class="form-select">
                        <option value="dewasa" selected>dewasa</option>
                        <option value="anak">anak</option>
                    </select>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Submit Gaun</button>
            </form>
            <br>
        </div>
    </div>

    {{-- please write a jquery script to fullfill my requirement --}}
    <script>
        $(document).ready(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Attach keyup event listener to the "kode-gaun-input" field
            $('#kode-gaun-input').on('keyup', function() {
                var kode = $(this).val();
                console.log(kode);

                // Make an AJAX request to check if the kode is already registered
                if (kode.trim() === '') {
                    // If the kode is empty, show the error state
                    $('#kode-gaun-input').removeClass('is-valid');
                    $('#kode-gaun-input').addClass('is-invalid');
                    $('#kode-gaun-input').next('.invalid-feedback').remove();
                    $('#kode-gaun-input').after(
                        '<div class="invalid-feedback">Kode tidak boleh kosong.</div>');
                } else {
                    $.ajax({
                        url: '{{ route('check-kode-gaun') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            kode: kode
                        },
                        success: function(data) {
                            // If the kode is already registered, show the error icon
                            if (data == true && kode != '') {
                                $('#kode-gaun-input').removeClass('is-valid');
                                $('#kode-gaun-input').addClass('is-invalid');
                                $('#kode-gaun-input').next('.invalid-feedback').remove();
                                $('#kode-gaun-input').after(
                                    '<div class="invalid-feedback">Kode sudah terdaftar.</div>'
                                );
                            } else {
                                // If the kode is not registered, show the success icon
                                $('#kode-gaun-input').removeClass('is-invalid');
                                $('#kode-gaun-input').addClass('is-valid');
                                $('#kode-gaun-input').next('.invalid-feedback').remove();
                            }
                        },
                        error: function() {
                            // Handle any errors that occur during the AJAX request
                            console.error('Error checking kode availability.');
                        }
                    });
                }
            });

            $('form').on('submit', function(e) {
                // Check if the kode-gaun-input field has the is-invalid class
                if ($('#kode-gaun-input').hasClass('is-invalid')) {
                    e.preventDefault(); // Prevent form submission
                    alert(
                        'Kode sudah terdaftar atau kode kosong. Harap cek kembali kode yang ingin didaftarkan'
                        ); // Show alert message
                }
            });

            // Drag and drop function
            var previewBox = $('#gaun-preview-box');
            var fileInput = $('#gambar-gaun-input');

            // Handle drag and drop events
            previewBox.on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('bg-light');
            });

            previewBox.on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('bg-light');
            });

            previewBox.on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('bg-light');

                var files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    previewImage(files[0]);
                    fileInput.prop('files', files);
                }
            });

            // Handle manual file selection
            previewBox.on('click', function() {
                fileInput.click();
            });

            fileInput.on('change', function() {
                var file = this.files[0];
                previewImage(file);
            });

            function previewImage(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    previewBox.html('<img src="' + e.target.result +
                        '" class="img-fluid" style="width: 15vw; height: 20vw">');
                    $('#selected-file-name').text(file.name);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
