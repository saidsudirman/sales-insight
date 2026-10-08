<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SalesInsight</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: #f7f8fa;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
        }

        .login-left {
            width: 50%;
            background: linear-gradient(
                135deg,
                #111827 0%,
                #1f2937 50%,
                #374151 100%
            );
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px;
        }

        .brand-content {
            max-width: 500px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: #c9b458;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #111827;
            margin-bottom: 25px;
        }

        .brand-content h1 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .brand-content p {
            color: #d1d5db;
            line-height: 1.8;
            font-size: 16px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 25px;
            color: #e5e7eb;
        }

        .feature i {
            color: #c9b458;
            width: 20px;
        }

        .login-right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
        }

        .login-card h2 {
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #6b7280;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            height: 52px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 0 15px;
        }

        .form-control:focus {
            border-color: #c9b458;
            box-shadow: 0 0 0 3px rgba(201, 180, 88, 0.15);
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .input-icon {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-right: none;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            border-radius: 10px 0 0 10px;
        }

        .btn-login {
            height: 52px;
            border: none;
            border-radius: 10px;
            background: #c9b458;
            color: #111827;
            font-weight: 700;
            width: 100%;
            transition: .2s;
        }

        .btn-login:hover {
            background: #b09a3e;
            color: white;
        }

        .remember {
            font-size: 14px;
            color: #6b7280;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .copyright {
            text-align: center;
            color: #9ca3af;
            font-size: 13px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .login-wrapper {
                display: block;
            }

            .login-left {
                width: 100%;
                min-height: 280px;
                padding: 35px;
            }

            .brand-content h1 {
                font-size: 32px;
            }

            .feature {
                display: none;
            }

            .login-right {
                width: 100%;
                min-height: calc(100vh - 280px);
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <div class="login-left">

        <div class="brand-content">

            <div class="brand-icon">
                <i class="fa-solid fa-chart-line"></i>
            </div>

            <h1>SalesInsight</h1>

            <p>
                Dashboard Analitik Penjualan untuk mendukung
                pengambilan keputusan pada UMKM MIYA.
            </p>

            <div class="feature">
                <i class="fa-solid fa-chart-column"></i>
                <span>Analisis tren penjualan</span>
            </div>

            <div class="feature">
                <i class="fa-solid fa-box"></i>
                <span>Monitoring produk dan stok</span>
            </div>

            <div class="feature">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Informasi penjualan yang mudah dipahami</span>
            </div>

        </div>

    </div>


    <div class="login-right">

        <div class="login-card">

            <h2>Selamat Datang</h2>

            <p class="login-subtitle">
                Masuk ke dashboard SalesInsight
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>

                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form
                action="{{ route('login.process') }}"
                method="POST"
            >

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <div class="input-group">

                        <span class="input-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </span>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="Masukkan email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                    </div>

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Password
                    </label>

                    <div class="input-group">

                        <span class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </span>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required
                        >

                        <button
                            type="button"
                            class="btn btn-light"
                            onclick="togglePassword()"
                            style="
                                border:1px solid #e5e7eb;
                                border-left:none;
                                border-radius:0 10px 10px 0;
                            "
                        >
                            <i
                                class="fa-solid fa-eye"
                                id="passwordIcon"
                            ></i>
                        </button>

                    </div>

                </div>


                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="form-check">

                        <input
                            type="checkbox"
                            name="remember"
                            class="form-check-input"
                            id="remember"
                        >

                        <label
                            class="form-check-label remember"
                            for="remember"
                        >
                            Ingat saya
                        </label>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn-login"
                >
                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                    Masuk ke Dashboard
                </button>

            </form>

            <div class="copyright">
                © {{ date('Y') }} SalesInsight · UMKM MIYA
            </div>

        </div>

    </div>

</div>


<script>

function togglePassword() {

    const password = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');

    if (password.type === 'password') {

        password.type = 'text';

        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');

    } else {

        password.type = 'password';

        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');

    }

}

</script>

</body>
</html>