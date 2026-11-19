
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Koperasiku Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 255px;
            background: #172554;
            color: white;
            padding: 28px 18px;
            flex-shrink: 0;
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            padding: 0 12px 30px;
            border-bottom: 1px solid #334477;
            letter-spacing: .5px;
        }

        .brand span {
            color: #60a5fa;
        }

        .menu-label {
            color: #94a3b8;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1.5px;
            margin: 30px 12px 14px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 14px;
            margin-bottom: 7px;
            color: #dbeafe;
            text-decoration: none;
            border-radius: 9px;
            font-size: 14px;
            transition: .2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #2563eb;
            color: white;
        }

        .menu-icon {
            width: 22px;
            text-align: center;
            font-size: 17px;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            height: 76px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            box-shadow: 0 2px 10px rgba(0,0,0,.04);
        }

        .topbar h2 {
            font-size: 18px;
            margin: 0;
            font-weight: 700;
        }

        .admin-badge {
            background: #eff6ff;
            color: #1d4ed8;
            padding: 9px 16px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .content {
            padding: 32px 35px;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .page-heading h1 {
            margin: 0 0 8px;
            font-size: 27px;
            font-weight: 750;
            color: #172554;
        }

        .page-heading p {
            color: #64748b;
            margin: 0;
            font-size: 14px;
        }

        /* ALERT */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(15,23,42,.05);
            margin-bottom: 22px;
        }

        .card h3 {
            margin: 0 0 20px;
            font-size: 17px;
            color: #1e293b;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        tr:hover td {
            background: #f8fafc;
        }

        /* FORM */
        input, select {
            padding: 11px 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            max-width: 100%;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #dbeafe;
        }

        button, .btn {
            display: inline-block;
            padding: 11px 18px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        button:hover, .btn:hover {
            background: #1d4ed8;
        }

        .btn-reset {
            background: #e2e8f0;
            color: #334155;
        }

        .btn-reset:hover {
            background: #cbd5e1;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
                padding: 20px 10px;
            }

            .content {
                padding: 20px 15px;
            }

            .topbar {
                padding: 0 15px;
            }

            .card {
                padding: 16px;
                overflow-x: auto;
            }
        }

        @media (max-width: 520px) {
            .sidebar {
                width: 65px;
                padding: 20px 7px;
            }

            .brand {
                font-size: 13px;
                padding: 0 3px 20px;
            }

            .sidebar a {
                justify-content: center;
                padding: 12px 4px;
                font-size: 0;
            }

            .menu-label {
                font-size: 0;
            }

            .menu-icon {
                font-size: 19px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="admin-wrapper">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="brand">
            KOPERASI<span>KU.</span>
        </div>

        <div class="menu-label">MENU UTAMA</div>

        <a href="{{ route('dashboard') }}">
            <span class="menu-icon">⌂</span> Dashboard
        </a>

        <div class="menu-label">MANAJEMEN DATA</div>

        <a href="{{ route('anggotas.index') }}">
            <span class="menu-icon">♙</span> Data Anggota
        </a>

        <a href="{{ route('pinjaman.index') }}">
            <span class="menu-icon">▣</span> Data Pinjaman
        </a>

        <a href="{{ route('angsuran.index') }}">
            <span class="menu-icon">◷</span> Data Angsuran
        </a>

        <a href="{{ route('simpanans.index') }}">
            <span class="menu-icon">▤</span> Data Simpanan
        </a>

        <div class="menu-label">LAPORAN</div>

        <a href="{{ route('laporan.pinjaman') }}">
            <span class="menu-icon">▥</span> Laporan Pinjaman
        </a>

    </aside>

    <!-- MAIN -->
    <main class="main-content">

        <header class="topbar">
            <h2>@yield('header', 'Dashboard')</h2>
            <div class="admin-badge">● Administrator</div>
        </header>

        <section class="content">

            @if(session('success'))
                <div class="alert alert-success">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </section>

    </main>

</div>

@stack('scripts')

</body>
</html>
