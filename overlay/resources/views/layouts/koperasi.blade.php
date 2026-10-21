<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'KoperasiKu')</title>
    @vite(['resources/css/koperasi.css', 'resources/js/koperasi.js'])
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold" href="{{ route('anggota.index') }}">KoperasiKu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Buka navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('anggota.index') }}">Data Anggota</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('simpanan.index') }}">Simpanan</a></li>
                @if(auth()->user()?->role === 'admin')
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Manajemen User</a></li>
                @endif
            </ul>
            @auth
                <span class="navbar-text text-white me-3">{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
<main>
    <div class="container-fluid px-4 pt-3">
        @if(session('success'))<div class="alert alert-success mb-0">{{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger mb-0">{{ session('error') }}</div>@endif
    </div>
    @yield('content')
</main>
<footer class="text-center text-muted py-4"><small>&copy; {{ date('Y') }} KoperasiKu</small></footer>
</body>
</html>
