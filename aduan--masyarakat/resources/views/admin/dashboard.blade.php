@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_users'] }}</h4>
                        <p>Total Users</p>
                    </div>
                    <div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.users') }}" class="text-white text-decoration-none">
                    <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success">
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
            <div class="card-footer">
                <a href="{{ route('admin.reports') }}" class="text-white text-decoration-none">
                    <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_articles'] }}</h4>
                        <p>Total Artikel</p>
                    </div>
                    <div>
                        <i class="fas fa-newspaper fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('articles.index') }}" class="text-white text-decoration-none">
                    <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['pending_reports'] }}</h4>
                        <p>Laporan Pending</p>
                    </div>
                    <div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="text-white text-decoration-none">
                    <small>Lihat Detail <i class="fas fa-arrow-right"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Status Overview -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Status Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <h3 class="text-warning">{{ $stats['pending_reports'] }}</h3>
                        <p>Menunggu</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h3 class="text-info">{{ $stats['in_progress_reports'] }}</h3>
                        <p>Diproses</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h3 class="text-success">{{ $stats['resolved_reports'] }}</h3>
                        <p>Selesai</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <h3 class="text-danger">{{ $stats['rejected_reports'] }}</h3>
                        <p>Ditolak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Kategori Laporan</h5>
            </div>
            <div class="card-body">
                @foreach($category_stats as $category)
                <div class="d-flex justify-content-between mb-2">
                    <span>{{ ucfirst($category->category) }}</span>
                    <span class="badge bg-primary">{{ $category->count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Laporan Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($recent_reports as $report)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">{{ Str::limit($report->title, 30) }}</h6>
                        <small class="text-muted">{{ $report->user->name }} - {{ $report->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'resolved' ? 'success' : 'info') }}">
                        {{ $report->status }}
                    </span>
                </div>
                @empty
                <p class="text-muted">Belum ada laporan</p>
                @endforelse
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>User Terbaru</h5>
            </div>
            <div class="card-body">
                @forelse($recent_users as $newUser)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1">{{ $newUser->name }}</h6>
                        <small class="text-muted">{{ $newUser->email }} - {{ $newUser->created_at->diffForHumans() }}</small>
                    </div>
                    <span class="badge bg-secondary">{{ $newUser->role }}</span>
                </div>
                @empty
                <p class="text-muted">Belum ada user baru</p>
                @endforelse
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
        </div>
    </div>
</div>
@endsection
