<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SiADU</title>
    
    {{-- CSS Utama --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- 1. TAMBAHKAN INI: Placeholder untuk CSS dari halaman lain --}}
    @stack('styles')

</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar vh-100">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold"><i class="fas fa-comments text-primary me-2"></i>SiADU</h4>
                        @auth {{-- Pastikan user login sebelum mengakses propertinya --}}
                            <p class="text-muted mb-1">{{ Auth::user()->name }}</p>
                            <span class="badge bg-{{ Auth::user()->isAdmin() ? 'success' : 'secondary' }}">
                                {{ Auth::user()->isAdmin() ? 'Admin' : 'Warga' }}
                            </span>
                        @endauth
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt fa-fw me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-file-alt fa-fw me-2"></i> 
                                {{ Auth::user()->isAdmin() ? 'Kelola Laporan' : 'Laporan Saya' }}
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('articles.create') ? 'active' : '' }}" href="{{ route('articles.create') }}">
                                    <i class="fas fa-plus fa-fw me-2"></i> Buat Artikel
                                </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('articles.index') ? 'active' : '' }}" href="{{ route('articles.index') }}">
                                <i class="fas fa-newspaper fa-fw me-2"></i> Artikel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog fa-fw me-2"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item mt-3 border-top pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-danger text-start w-100">
                                    <i class="fas fa-sign-out-alt fa-fw me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Script Utama --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- 2. UBAH INI: dari @yield menjadi @stack untuk menampung banyak script --}}
    @stack('scripts')
</body>
</html>