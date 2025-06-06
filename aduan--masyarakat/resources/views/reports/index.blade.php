@extends('layouts.dashboard')

@section('title', 'Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $user->isAdmin() ? 'Kelola Laporan' : 'Laporan Saya' }}</h1>
    @if(!$user->isAdmin())
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Buat Laporan
        </a>
    </div>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari laporan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="infrastruktur" {{ request('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                        <option value="lingkungan" {{ request('category') == 'lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                        <option value="pelayanan" {{ request('category') == 'pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                        <option value="keamanan" {{ request('category') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-body">
        @if($reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        @if($user->isAdmin())
                        <th>Pelapor</th>
                        @endif
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>#{{ $report->id }}</td>
                        <td>{{ Str::limit($report->title, 50) }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($report->category) }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'in-progress' => 'info',
                                    'resolved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $statusLabel = [
                                    'pending' => 'Menunggu',
                                    'in-progress' => 'Diproses',
                                    'resolved' => 'Selesai',
                                    'rejected' => 'Ditolak'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusClass[$report->status] }}">
                                {{ $statusLabel[$report->status] }}
                            </span>
                        </td>
                        @if($user->isAdmin())
                        <td>{{ $report->user->name }}</td>
                        @endif
                        <td>{{ $report->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$user->isAdmin() && $report->status == 'pending')
                            <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $reports->links() }}
        @else
        <div class="text-center py-4">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5>Belum ada laporan</h5>
            <p class="text-muted">{{ $user->isAdmin() ? 'Belum ada laporan yang masuk.' : 'Anda belum membuat laporan apapun.' }}</p>
            @if(!$user->isAdmin())
            <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan Pertama</a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
