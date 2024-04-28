<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edelweiss App</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('source/images/logos/logo.jpg') }}" />
    <link rel="stylesheet" href="{{ asset('source/css/styles.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('source/css/main.scss') }}" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-header">
                                @error('invalid')
                                    <div class="alert alert-danger">
                                        <strong>Error:</strong> {{ $message }}
                                    </div>
                                @enderror
                                @if (session('logged-out'))
                                    <div class="alert alert-success">
                                        {{ session('logged-out') }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('source/images/logos/logo.jpg') }}" width="180"
                                        alt="">
                                    <h1 class="title">Edelweiss</h1>
                                </a>
                                <p class="text-center">Admin page for Edelweiss App!</p>
                                <form action="" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control" aria-describedby="emailHelp"
                                            name="email">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                    <button type="submit"
                                        class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">SignIn</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('source/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('source/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
