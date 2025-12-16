<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('https://faq.asabri.co.id/assets/images/Logo_asabri.png') }}">
    <title>ASABRI Portfolio</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --asabri-navy: #003A70;
            --asabri-blue: #005A9E;
            --asabri-light: #f5f6fa;
            --asabri-grey: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--asabri-light);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: var(--asabri-navy);
            padding: 6px 0;
            transition: all 0.3s;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .logo {
            text-align: center;
            padding: 6px;
            color: white;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 25px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.3s;
            margin: 5px 10px;
            border-radius: 8px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link.active {
            background: var(--asabri-blue);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Header */
        .top-header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-header h4 {
            color: var(--asabri-navy);
            font-weight: 600;
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        padding: 6px 12px;
        font-weight: 500;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card h3 {
            font-size: 32px;
            font-weight: 700;
            color: var(--asabri-navy);
            margin: 10px 0;
        }

        .stat-card p {
            color: var(--asabri-grey);
            margin: 0;
            font-size: 14px;
        }

        /* Buttons */
        .btn-asabri {
            background: var(--asabri-navy);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-asabri:hover {
            background: var(--asabri-blue);
            color: white;
        }

        /* Tables */
        .table-wrapper {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .table thead {
            background: var(--asabri-light);
        }

        .table thead th {
            color: var(--asabri-navy);
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .content-wrapper {
            padding: 30px;
        }
    </style>
</head>

<body>
    @include('layouts.sidebar')

    <div class="main-content">
        <div class="top-header">
            <h4>{{ $pageTitle ?? 'Dashboard' }}</h4>


            @auth
                <div class="user-info">
                    <span class="badge 
                                                                            @if(auth()->user()->role === 'superadmin') bg-danger
                                                                            @elseif(auth()->user()->role === 'admin') bg-warning
                                                                            @else bg-secondary
                                                                            @endif">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                    <span>{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="user-info">
                    <a href="{{ route('login') }}" class="btn btn-asabri">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
                </div>
            @endauth
        </div>

        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>