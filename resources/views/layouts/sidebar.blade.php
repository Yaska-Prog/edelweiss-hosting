<!-- Sidebar Start -->

<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center">
            <img src="{{ asset('source/images/logos/logo.jpg') }}" width="100" height="100" alt=""
                style="display: block; margin: 0 auto;">
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('gaun.index') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-layout-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('pemesanan.index') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-calendar"></i>
                        </span>
                        <span class="hide-menu">Jadwal Pemesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('gaun.create') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-shirt"></i>
                        </span>
                        <span class="hide-menu">Tambah Data Gaun</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('pemesanan.create') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-table"></i>
                        </span>
                        <span class="hide-menu">Tambah Data Pemesanan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('availability-screen') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-circle-check"></i>
                        </span>
                        <span class="hide-menu">Cek Availability</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Authentication</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('grant-access') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-user"></i>
                        </span>
                        <span class="hide-menu">Berikan Akses</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href={{ route('logout') }} aria-expanded="false">
                        <span>
                            <i class="ti ti-logout"></i>
                        </span>
                        <span class="hide-menu">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
