@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard {{ $isAdmin ? 'Admin' : 'User' }}</h1>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Total Laporan</h6>
                            <h2 class="mb-0">{{ $totalReports }}</h2>
                        </div>
                        <i class="fas fa-file-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Dalam Proses</h6>
                            <h2 class="mb-0">{{ $inProgressReports }}</h2>
                        </div>
                        <i class="fas fa-spinner fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Selesai</h6>
                            <h2 class="mb-0">{{ $resolvedReports }}</h2>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Pengguna Aktif</h6>
                            <h2 class="mb-0">{{ $userCount }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        @if(!$isAdmin)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-plus text-primary me-2"></i>Buat Laporan Baru</h5>
                    <p class="card-text">Sampaikan pengaduan atau aspirasi Anda</p>
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan</a>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-alt text-success me-2"></i>{{ $isAdmin ? 'Kelola Laporan' : 'Lihat Laporan Saya' }}</h5>
                    <p class="card-text">{{ $isAdmin ? 'Tinjau dan tanggapi laporan masyarakat' : 'Pantau status laporan yang telah dibuat' }}</p>
                    <a href="{{ route('reports.index') }}" class="btn btn-success">Lihat Laporan</a>
                </div>
            </div>
        </div>
        @if($isAdmin)
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-newspaper text-info me-2"></i>Buat Artikel</h5>
                    <p class="card-text">Buat artikel informatif tentang laporan</p>
                    <a href="{{ route('articles.create') }}" class="btn btn-info text-white">Buat Artikel</a>
                </div>
            </div>
        </div>
        @endif
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user text-warning me-2"></i>Pengaturan Profil</h5>
                    <p class="card-text">Kelola informasi akun Anda</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit Profil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Laporan Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                        <tr>
                                            <td>{{ $report->title }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($report->category) }}</span>
                                            </td>
                                            <td>
                                                @if($report->status == 'pending')
                                                    <span class="badge badge-pending">Menunggu</span>
                                                @elseif($report->status == 'in-progress')
                                                    <span class="badge badge-in-progress">Diproses</span>
                                                @elseif($report->status == 'resolved')
                                                    <span class="badge badge-resolved">Selesai</span>
                                                @elseif($report->status == 'rejected')
                                                    <span class="badge badge-rejected">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>{{ $report->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center py-3">Belum ada laporan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection