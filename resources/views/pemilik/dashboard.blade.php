<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - SalesInsight</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container py-5">

    <h1>Dashboard Admin</h1>

    <p>
        Selamat datang, {{ auth()->user()->name }}
    </p>

    <p>
        Role:
        <strong>{{ auth()->user()->role }}</strong>
    </p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button class="btn btn-danger">
            Logout
        </button>
    </form>

</div>

</body>
</html>