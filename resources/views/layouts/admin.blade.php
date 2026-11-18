
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - MIYA Sales Insight</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #B09A3E;
            --primary-dark: #97812F;
            --sidebar: #1E293B;
            --sidebar-hover: #334155;
            --background: #F8FAFC;
            --text: #1E293B;
            --muted: #94A3B8;
            --border: #E2E8F0;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            min-height: 100%;
            margin: 0;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-size: 14px;
        }

        /* =================================
           MAIN LAYOUT
        ================================= */

        .app-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            min-height: 100vh;
        }

        /* =================================
           SIDEBAR
        ================================= */

        .sidebar {
            width: 260px;
            min-width: 260px;
            height: 100vh;
            flex-shrink: 0;
            background: var(--sidebar) !important;
            color: #FFFFFF;
            border: none !important;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Brand */

        .brand {
            height: 90px;
            min-height: 90px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            background: var(--primary);
            color: #FFFFFF;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: 1px;
            line-height: 1.4;
        }

        .brand-subtitle {
            font-size: 9px;
            color: #CBD5E1;
            letter-spacing: 2px;
        }

        /* Sidebar Body */

        .sidebar .offcanvas-body {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
            min-height: 0;
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            background: var(--sidebar);
        }

        .sidebar-menu {
            padding: 10px 14px 20px;
            flex: 1 0 auto;
        }

        .menu-label {
            color: #94A3B8;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.3px;
            padding: 15px 10px 9px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 42px;
            padding: 11px 13px;
            margin-bottom: 5px;
            color: #CBD5E1;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            background: transparent;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            line-height: 1.5;
            transition: all .2s ease;
            cursor: pointer;
        }

        .sidebar-link i {
            width: 19px;
            min-width: 19px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #FFFFFF;
        }

        .sidebar-link.active {
            background: var(--primary);
            color: #FFFFFF;
            font-weight: 700;
        }

        /* Sidebar Footer */

        .sidebar-footer {
            width: 100%;
            flex-shrink: 0;
            margin-top: auto;
            padding: 14px 14px 18px;
            background: var(--sidebar);
            border-top: 1px solid #334155;
        }

        .sidebar-footer form {
            width: 100%;
            margin: 0;
        }

        .sidebar-footer .sidebar-link {
            margin: 0;
        }

        .sidebar-footer .sidebar-link:hover {
            background: var(--sidebar-hover) !important;
            color: #FFFFFF !important;
        }

        .sidebar-copyright {
            text-align: center;
            color: #94A3B8;
            font-size: 10px;
            line-height: 1.6;
            margin-top: 12px;
        }

        /* =================================
           NAVBAR
        ================================= */

        .topbar {
            height: 72px;
            min-height: 72px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-breadcrumb {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            border-radius: 50%;
            background: #F3EBD0;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
        }

        .user-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
        }

        .user-role {
            font-size: 10px;
            color: var(--muted);
            margin-top: 2px;
        }

        .btn-sidebar-toggle {
            width: 38px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #FFFFFF;
            color: var(--text);
        }

        .btn-sidebar-toggle:hover {
            background: #F1F5F9;
        }

        /* =================================
           PAGE CONTENT
        ================================= */

        .page-content {
            flex: 1 0 auto;
            padding: 28px;
            min-width: 0;
        }

        .page-heading {
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 5px;
            color: var(--text);
        }

        .page-description {
            color: var(--muted);
            font-size: 12px;
        }

        /* =================================
           BUTTON
        ================================= */

        .btn-primary-custom {
            background: var(--primary);
            color: #FFFFFF;
            border: 1px solid var(--primary);
        }

        .btn-primary-custom:hover,
        .btn-primary-custom:focus {
            background: var(--primary-dark);
            color: #FFFFFF;
            border-color: var(--primary-dark);
        }

        /* =================================
           MAIN FOOTER
        ================================= */

        .main-footer {
            min-height: 60px;
            padding: 18px 28px;
            color: var(--muted);
            font-size: 11px;
            border-top: 1px solid var(--border);
            background: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        /* =================================
           RESPONSIVE DESKTOP
        ================================= */

        @media (min-width: 992px) {

            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh !important;
                min-height: 100vh;
                visibility: visible !important;
                transform: none !important;
            }

            .sidebar .offcanvas-header {
                display: none !important;
            }

            .sidebar > .brand {
                display: flex !important;
            }

            .sidebar .offcanvas-body {
                display: flex !important;
                height: auto;
                flex: 1 1 auto;
            }
        }

        /* =================================
           RESPONSIVE MOBILE
        ================================= */

        @media (max-width: 991.98px) {

            .sidebar {
                width: 280px;
                min-width: 280px;
                height: 100vh !important;
                max-width: 85vw;
                border: none !important;
            }

            .sidebar .offcanvas-header {
                display: flex;
                min-height: 75px;
                padding: 15px 20px;
                background: var(--sidebar);
                border-bottom: 1px solid #334155 !important;
            }

            .sidebar .offcanvas-header .brand {
                height: auto;
                min-height: 0;
                padding: 0;
            }

            .sidebar > .brand {
                display: none !important;
            }

            .sidebar .offcanvas-body {
                display: flex;
                height: auto;
                flex: 1 1 auto;
            }

            .topbar {
                padding: 0 16px;
            }

            .page-content {
                padding: 22px 16px;
            }

            .main-footer {
                padding: 16px;
            }
        }

        @media (max-width: 575.98px) {

            .topbar {
                height: 65px;
                min-height: 65px;
            }

            .page-heading {
                font-size: 20px;
            }

            .main-footer {
                justify-content: center;
                text-align: center;
            }
        }

    </style>

    @stack('styles')

</head>

<body>

<div class="app-container">

    <!-- =================================
         SIDEBAR
    ================================== -->

    <aside class="offcanvas-lg offcanvas-start sidebar"
           tabindex="-1"
           id="sidebar"
           aria-label="Sidebar Navigation">

        <!-- MOBILE BRAND -->
        <div class="offcanvas-header">

            <div class="brand">

                <div class="brand-icon">
                    <i class="fa-solid fa-chart-line"></i>
                </div>

                <div>
                    <div class="brand-name">MIYA</div>
                    <div class="brand-subtitle">SALES INSIGHT</div>
                </div>

            </div>

            <button type="button"
                    class="btn-close btn-close-white d-lg-none"
                    data-bs-dismiss="offcanvas"
                    aria-label="Close">
            </button>

        </div>

        <!-- DESKTOP BRAND -->
        <div class="brand d-none d-lg-flex">

            <div class="brand-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <div>
                <div class="brand-name">MIYA</div>
                <div class="brand-subtitle">SALES INSIGHT</div>
            </div>

        </div>

        <!-- SIDEBAR CONTENT -->
        <div class="offcanvas-body">

            <nav class="sidebar-menu">

                <!-- MENU UTAMA -->
                <div class="menu-label">MENU UTAMA</div>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>

                </a>

                <!-- MASTER DATA -->
                <div class="menu-label">MASTER DATA</div>

                <a href="{{ route('admin.kategoris.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-tags"></i>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.produks.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-box"></i>
                    <span>Produk</span>
                </a>

                <a href="{{ route('admin.ukurans.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-ruler"></i>
                    <span>Ukuran</span>
                </a>

                <a href="{{ route('admin.warnas.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-palette"></i>
                    <span>Warna</span>
                </a>

                <a href="{{ route('admin.varian-produks.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Varian Produk</span>
                </a>

                <a href="{{ route('admin.pelanggans.index') }}" class="sidebar-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Pelanggan</span>
                </a>

                <!-- PENJUALAN -->
                <div class="menu-label">PENJUALAN</div>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Transaksi</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Transaksi</span>
                </a>

            </nav>

            <!-- SIDEBAR FOOTER -->
            <div class="sidebar-footer">

                <form action="{{ route('logout') }}" method="POST">

                    @csrf

                    <button type="submit" class="sidebar-link">

                        <i class="fa-solid fa-right-from-bracket"></i>

                        <span>Keluar</span>

                    </button>

                </form>

                <div class="sidebar-copyright">
                    MIYA Sales Insight<br>
                    &copy; {{ date('Y') }} All Rights Reserved
                </div>

            </div>

        </div>

    </aside>

    <!-- =================================
         MAIN WRAPPER
    ================================== -->

    <div class="main-wrapper">

        <!-- NAVBAR -->
        <nav class="topbar">

            <div class="d-flex align-items-center gap-3">

                <!-- MOBILE TOGGLE -->
                <button class="btn-sidebar-toggle d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebar"
                        aria-controls="sidebar"
                        aria-label="Buka menu">

                    <i class="fa-solid fa-bars"></i>

                </button>

                <div>

                    <div class="topbar-title">
                        @yield('page-title', 'Dashboard')
                    </div>

                    <div class="topbar-breadcrumb">
                        MIYA / @yield('breadcrumb', 'Dashboard')
                    </div>

                </div>

            </div>

            <!-- PROFILE -->
            <div class="d-flex align-items-center gap-3">

                <div class="text-end d-none d-sm-block">

                    <div class="user-name">
                        {{ auth()->user()->name }}
                    </div>

                    <div class="user-role">
                        {{ ucfirst(auth()->user()->role) }}
                    </div>

                </div>

                <div class="profile-avatar">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>

            </div>

        </nav>

        <!-- PAGE CONTENT -->
        <main class="page-content">

            @yield('content')

        </main>

        <!-- MAIN FOOTER -->
        <footer class="main-footer">

            <span>
                Copyright &copy; {{ date('Y') }} MIYA Sales Insight.
            </span>

            <span>
                Sales Management System
            </span>

        </footer>

    </div>

</div>

<!-- BOOTSTRAP JAVASCRIPT -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
