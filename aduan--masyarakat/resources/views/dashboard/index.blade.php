<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SiADU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-comments text-primary"></i> SiADU</h4>
                        <p class="text-muted">{{ $user->name }}</p>
                        <span class="badge bg-{{ $user->isAdmin() ? 'primary' : 'secondary' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'User' }}
                        </span>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="fas fa-file-alt"></i> 
                                {{ $user->isAdmin() ? 'Kelola Laporan' : 'Laporan Saya' }}
                            </a>
                        </li>
                        @if(!$user->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.create') }}">
                                <i class="fas fa-plus"></i> Buat Laporan
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('articles.index') }}">
                                <i class="fas fa-newspaper"></i> Artikel
                            </a>
                        </li>
                        @if($user->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('articles.create') }}">
                                <i class="fas fa-plus"></i> Buat Artikel
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard {{ $user->isAdmin() ? 'Admin' : 'User' }}</h1>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $stats['total_reports'] }}</h4>
                                        <p>Total Laporan</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-file-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $stats['pending_reports'] }}</h4>
                                        <p>Menunggu</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $stats['in_progress_reports'] }}</h4>
                                        <p>Diproses</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-spinner fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $stats['resolved_reports'] }}</h4>
                                        <p>Selesai</p>
                                    </div>
                                    <div>
                                        <i class="fas fa-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row">
                    @if(!$user->isAdmin())
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-plus fa-3x text-primary mb-3"></i>
                                <h5>Buat Laporan Baru</h5>
                                <p>Sampaikan pengaduan atau aspirasi Anda</p>
                                <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-list fa-3x text-success mb-3"></i>
                                <h5>{{ $user->isAdmin() ? 'Kelola Laporan' : 'Lihat Laporan Saya' }}</h5>
                                <p>{{ $user->isAdmin() ? 'Tinjau dan tanggapi laporan masyarakat' : 'Pantau status laporan yang telah dibuat' }}</p>
                                <a href="{{ route('reports.index') }}" class="btn btn-success">Lihat Laporan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
