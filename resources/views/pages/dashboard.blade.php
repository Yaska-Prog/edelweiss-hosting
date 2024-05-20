@extends('layouts.main')

@section('header')
    <header class="app-header">
        <nav class="navbar navbar-expand-lg">
            <h1 class="title">Dashboard</h1>
        </nav>
    </header>
    <style>
        .edit-gaun-btn {
            margin-right: 20px;
        }
    </style>
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
    <div id="dashboard" class="row">
        <div class="card">
            <div class="card-body">
                <label class="form-label">Tipe Atribut</label>
                <select name="param" id="search-gaun-param" class="form-select">
                    <option value="kode" selected>kode</option>
                    <option value="warna">warna</option>
                    <option value="usia">usia</option>
                </select>
                <div id="searchInput" class="mt-2">
                    <label class="form-label">Cari Gaun</label>
                    <input id="searchQuery" type="text" class="form-control" placeholder="Masukkan kode gaun">
                    <div class="form-text">Masukkan kode gaun</div>
                </div>
                <div id="usiaSelect" class="mt-2 d-none">
                    <label class="form-label">Usia Pengguna Gaun</label>
                    <select id="usiaOption" class="form-select">
                        <option value="Dewasa">Dewasa</option>
                        <option value="Anak">Anak</option>
                    </select>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div id="loadingIndicator" class="max-width: 100%; margin-left: auto; margin-right: auto;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="gaunList" class="row d-none">
                        <!-- Gaun items will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-gaun-modal" tabindex="-1" role="dialog" aria-labelledby="modalDashboardTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Gaun</h5>
                </div>
                <form id="edit-gaun-form" method="POST" action="{{ route('gaun.update', 'PLACEHOLDER') }}"
                    enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label class="mb-1" for="kode-gaun">Kode Gaun</label>
                            <input type="text" class="form-control" id="kode-gaun" name="kode" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="gambar-gaun">Gambar Gaun</label>
                            <div id="image-preview-box" class="border border-dashed p-3 mb-3">
                                <p class="text-muted mb-0">Drag and drop image here or click to upload</p>
                            </div>
                            <span id="selected-file-name"></span>
                            <input type="file" id="gambar-gaun" name="gambar" accept="image/*" style="display: none;">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="warna-gaun">Warna Gaun</label>
                            <input type="text" class="form-control" id="warna-gaun" name="warna">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="harga-gaun">Harga Gaun</label>
                            <input type="number" class="form-control" id="harga-gaun" name="harga">
                        </div>
                        <div class="form-group mb-2">
                            <label class="mb-1" for="usia-gaun">Usia Pengguna Gaun</label>
                            <select name="usia" id="usia-gaun" class="form-select">
                                <option value="Dewasa">Dewasa</option>
                                <option value="Anak">Anak</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete-gaun-modal" tabindex="-1" role="dialog" aria-labelledby="modalDashboardTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-gaun-modal-title">Delete Gaun</h5>
                </div>
                <form id="delete-gaun-form" method="POST" action="{{ route('gaun.destroy', 'PLACEHOLDER') }}"
                    enctype="multipart/form-data">
                    @method('DELETE')
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label class="mb-1" for="password-destroy-gaun">Confirmation Password</label>
                            <input type="password" class="form-control" id="password-destroy-gaun" name="password"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Delete Gaun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function formatCurrency(amount) {
                if (amount > 0) {
                    return 'Rp. ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                } else {
                    return 0;
                }
            }
            // Initial load of all gauns
            var gauns = @json($gauns);
            var baseUrl = "{{ asset('storage/') }}";

            // Function to render gauns based on search query
            function renderGauns(query, usia = null) {
                var selectedAttribute = $('#search-gaun-param').val();
                console.log(selectedAttribute);
                $('#loadingIndicator').removeClass('d-none');
                $('#gaunList').addClass('d-none');
                setTimeout(function() {
                    $('#loadingIndicator').addClass('d-none');
                    $('#gaunList').empty();
                    var filteredGauns = gauns.filter(function(gaun) {
                        if (selectedAttribute == 'usia') {
                            return gaun[selectedAttribute].toLowerCase() == usia.toLowerCase();
                        } else {
                            return gaun[selectedAttribute].toLowerCase().includes(query
                                .toLowerCase());
                        }
                    });
                    if (filteredGauns.length > 0) {
                        $('#gaunList').removeClass('d-none');
                        filteredGauns.forEach(function(gaun) {
                            $('#gaunList').append(
                                '<div class="col-md-4">' +
                                '<div class="card-body">' +
                                '<div class="card">' +
                                '<img src="' + baseUrl + '/' + gaun.gambar +
                                '" class="card-img-top" alt="...">' +
                                '<div class="card-body">' +
                                '<h5 class="card-title">' + gaun.kode + '</h5>' +
                                '<h6 class="card-text">Harga Gaun: ' + formatCurrency(gaun.harga) + '</h6>' +
                                '<h6 class="card-text">Warna Gaun: ' + gaun.warna + '</h6>' +
                                '<h6 class="card-text">Usia pengguna Gaun: ' + gaun.usia +
                                '</h6>' +
                                '<button type="button" data-toggle="modal" data-target="#edit-gaun-modal" data-gambar="' +
                                gaun.gambar + '" data-kode="' + gaun.kode + '" data-warna="' +
                                gaun.warna + '" data-harga="' + formatCurrency(gaun.harga) + '" data-usia="' +
                                gaun.usia +
                                '" class="btn btn-primary edit-gaun-btn">Edit</button>' +
                                '<button type="button" data-toggle="modal" data-target="#delete-gaun-modal" data-kode="' +
                                gaun.kode +
                                '" class="btn btn-danger delete-gaun-btn">Delete</button>' +
                                '</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>'
                            );
                        });
                    }
                }, 500);
            }

            // Initial rendering
            renderGauns('');

            // Handling input change event
            $('#searchQuery').on('input', function() {
                var query = $(this).val();
                renderGauns(query);
            });

            // Handling select change event
            $('#search-gaun-param').on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue == 'usia') {
                    $('#searchInput').addClass('d-none');
                    $('#usiaSelect').removeClass('d-none');
                    $('#usiaOption').on('change', function() {
                        var usiaValue = $(this).val();
                        renderGauns('', usiaValue);
                    });
                } else {
                    $('#searchInput').removeClass('d-none');
                    $('#usiaSelect').addClass('d-none');
                    renderGauns('');
                }
            });
            $('#gambar-gaun').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        displayImagePreview(e.target.result, input.files[0].name);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            // Drag and drop event handlers for image preview
            $('#image-preview-box').on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).addClass('border-primary');
            });

            $('#image-preview-box').on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('border-primary');
            });

            $('#image-preview-box').on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).removeClass('border-primary');
                var files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        displayImagePreview(event.target.result, files[0].name); // Pass file name
                    }
                    reader.readAsDataURL(files[0]);
                    $('#gambar-gaun')[0].files = files;
                }
            });

            // Function to display image preview in the image preview box
            function displayImagePreview(imageData, fileName) {
                $('#image-preview-box').html('<img src="' + imageData + '" class="img-fluid">');
                $('#selected-file-name').text(fileName); // Display selected file name
            }

            // Clear selected file and image preview when modal is closed
            $('#edit-gaun-modal').on('hidden.bs.modal', function() {
                $('#selected-file-name').text('');
                $('#image-preview-box').html(
                    '<p class="text-muted mb-0">Drag and drop image here or click to upload</p>');
            });

            $('#image-preview-box').on('click', function() {
                $('#gambar-gaun').click(); // Simulate click on hidden file input
            });

            // When editing a gaun, display the existing image in the preview box
            $(document).on('click', '.edit-gaun-btn', function() {
                var gambar = $(this).data('gambar');
                var kode = $(this).data('kode');
                var warna = $(this).data('warna');
                var harga = $(this).data('harga');
                var usia = $(this).data('usia');

                $('#edit-gaun-modal #kode-gaun').val(kode);
                $('#edit-gaun-modal #warna-gaun').val(warna);
                $('#edit-gaun-modal #harga-gaun').val(harga);
                $('#edit-gaun-modal #usia-gaun').val(usia);

                // Display existing image
                displayImagePreview(baseUrl + '/' + gambar, gambar);

                $('#edit-gaun-form').attr('action', '/gaun/' + kode)
            });

            $(document).on('click', '.delete-gaun-btn', function() {
                var kode = $(this).data('kode');
                $('#delete-gaun-modal-title').html('Delete gaun dengan kode ' + kode);
                $('#delete-gaun-form').attr('action', '/gaun/' + kode)
            });
        });
    </script>
@endsection
