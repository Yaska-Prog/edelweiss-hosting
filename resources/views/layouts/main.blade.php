<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edelweiss Admin</title>
    {{-- @notifyCss --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('source/images/logos/logo.jpg') }}" />
    <link rel="stylesheet" href="{{ asset('source/scss/styles.scss') }}" />
    <link rel="stylesheet" href="{{ asset('source/css/styles.min.css') }}" />

    <script src="{{ asset('source/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('source/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('source/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('source/js/app.min.js') }}"></script>
    <script src="{{ asset('source/libs/simplebar/dist/simplebar.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />

    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>

    {{-- <div id="map"></div> --}}
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('layouts.sidebar')
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @yield('header')
            <!--  Header End -->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>
