@extends('layouts.app')

@section('title', 'Dashboard - Pengaduan Masyarakat')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Dashboard</h1>
                <div>
                    <a href="{{ route('reports.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Laporan</h6>
                            <h2 class="mb-0">{{ $stats['total_reports'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Menunggu</h6>
                            <h2 class="mb-0">{{ $stats['pending_reports'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Diproses</h6>
                            <h2 class="mb-0">{{ $stats['in_progress_reports'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cog fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Selesai</h6>
                            <h2 class="mb-0">{{ $stats['resolved_reports'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus"></i> Buat Laporan
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('reports.index') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-list"></i> Lihat Semua
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('profile') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user"></i> Profil
                            </a>
                        </div>
                        @if(Auth::user()->isAdmin())
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger w-100">
                                <i class="fas fa-cogs"></i> Admin Panel
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Laporan Terbaru</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    @if($recent_reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Pelapor</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_reports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>
                                            <a href="{{ route('reports.show', $report->id) }}" class="text-decoration-none">
                                                {{ Str::limit($report->title, 30) }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $report->category }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($report->status) {
                                                    'pending' => 'bg-warning',
                                                    'in_progress' => 'bg-info',
                                                    'resolved' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };
                                                $statusText = match($report->status) {
                                                    'pending' => 'Menunggu',
                                                    'in_progress' => 'Diproses',
                                                    'resolved' => 'Selesai',
                                                    default => $report->status
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>{{ $report->user->name }}</td>
                                        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada laporan</h5>
                            <p class="text-muted">Mulai dengan membuat laporan pertama Anda</p>
                            <a href="{{ route('reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Laporan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: box-shadow 0.15s ease-in-out;
    }
    
    .bg-primary { background-color: #007bff !important; }
    .bg-warning { background-color: #ffc107 !important; }
    .bg-info { background-color: #17a2b8 !important; }
    .bg-success { background-color: #28a745 !important; }
</style>
@endpush