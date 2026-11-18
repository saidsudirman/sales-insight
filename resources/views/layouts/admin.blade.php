
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
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--background);
            color: var(--text);
            font-size: 14px;
        }

        .sidebar {
            width: 260px;
            background: var(--sidebar);
            color: white;
            border: none;
        }

        .sidebar .offcanvas-body {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .brand {
            padding: 24px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: var(--primary);
            color: white;
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
        }

        .brand-subtitle {
            font-size: 9px;
            color: #CBD5E1;
            letter-spacing: 2px;
        }

        .sidebar-menu {
            padding: 10px 14px;
        }

        .menu-label {
            color: #94A3B8;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.3px;
            padding: 15px 10px 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            margin-bottom: 5px;
            color: #CBD5E1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: .2s;
        }

        .sidebar-link i {
            width: 19px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-link.active {
            background: var(--primary);
            color: white;
            font-weight: 700;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px;
            border-top: 1px solid #334155;
        }

        .main-wrapper {
            min-width: 0;
            flex: 1;
        }

        .topbar {
            height: 72px;
            background: white;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 28px;
        }

        .page-content {
            padding: 28px;
        }

        .page-heading {
            font-size: 23px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .page-description {
            color: #94A3B8;
            font-size: 12px;
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #F3EBD0;
            color: var(--primary-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .user-name {
            font-size: 12px;
            font-weight: 700;
        }

        .user-role {
            font-size: 10px;
            color: #94A3B8;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: white;
        }

        .main-footer {
            padding: 18px 28px;
            color: #94A3B8;
            font-size: 11px;
            border-top: 1px solid #E2E8F0;
            background: white;
        }

        @media (max-width: 991.98px) {
            .page-content {
                padding: 20px 15px;
            }

            .topbar {
                padding: 0 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="d-flex min-vh-100">

    <!-- SIDEBAR -->
    <aside class="offcanvas-lg offcanvas-start sidebar"
           tabindex="-1"
           id="sidebar">

        <div class="offcanvas-header border-bottom border-secondary">
            <div class="brand p-0">
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

        <div class="brand d-none d-lg-flex">
            <div class="brand-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <div class="brand-name">MIYA</div>
                <div class="brand-subtitle">SALES INSIGHT</div>
            </div>
        </div>

        <div class="offcanvas-body p-0">

            <nav class="sidebar-menu">

                <div class="menu-label">MENU UTAMA</div>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    Dashboard
                </a>

                <div class="menu-label">MASTER DATA</div>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-tags"></i>
                    Kategori
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-box"></i>
                    Produk
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-ruler"></i>
                    Ukuran
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-palette"></i>
                    Warna
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-layer-group"></i>
                    Varian Produk
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-users"></i>
                    Pelanggan
                </a>

                <div class="menu-label">PENJUALAN</div>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Transaksi
                </a>

                <a href="#" class="sidebar-link">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Riwayat Transaksi
                </a>

            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit"
                            class="sidebar-link border-0 bg-transparent w-100 text-start">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Keluar
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-secondary">
                        MIYA Sales Insight &copy; {{ date('Y') }}
                    </small>
                </div>
            </div>

        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper d-flex flex-column">

        <!-- NAVBAR -->
        <nav class="topbar d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-3">

                <button class="btn btn-light d-lg-none"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div>
                    <div class="fw-bold">
                        @yield('page-title', 'Dashboard')
                    </div>
                    <small class="text-secondary">
                        MIYA / @yield('breadcrumb', 'Dashboard')
                    </small>
                </div>

            </div>

            <div class="d-flex align-items-center gap-3">

                <div class="text-end d-none d-sm-block">
                    <div class="user-name">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="user-role">
                        Administrator
                    </div>
                </div>

                <div class="profile-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

            </div>

        </nav>

        <!-- PAGE CONTENT -->
        <main class="page-content flex-grow-1">

            @yield('content')

        </main>

        <!-- FOOTER -->
        <footer class="main-footer">
            <div class="d-flex justify-content-between flex-wrap gap-2">
                <span>Copyright &copy; {{ date('Y') }} MIYA Sales Insight.</span>
                <span>Sales Management System</span>
            </div>
        </footer>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
