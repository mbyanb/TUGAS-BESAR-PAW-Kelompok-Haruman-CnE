@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Laporan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Report Details -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $report->title }}</h5>
        <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'in-progress' ? 'info' : ($report->status == 'resolved' ? 'success' : 'danger')) }}">
            {{ $report->status == 'pending' ? 'Menunggu' : ($report->status == 'in-progress' ? 'Diproses' : ($report->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
        </span>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <p class="mb-1"><strong>Kategori:</strong> {{ ucfirst($report->category) }}</p>
                <p class="mb-1"><strong>Lokasi:</strong> {{ $report->location ?: 'Tidak disebutkan' }}</p>
                <p class="mb-1"><strong>Tanggal Laporan:</strong> {{ $report->created_at->format('d M Y H:i') }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Pelapor:</strong> {{ $report->user->name }}</p>
                <p class="mb-1"><strong>Status:</strong> 
                    <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'in-progress' ? 'info' : ($report->status == 'resolved' ? 'success' : 'danger')) }}">
                        {{ $report->status == 'pending' ? 'Menunggu' : ($report->status == 'in-progress' ? 'Diproses' : ($report->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
                    </span>
                </p>
                @if($report->updated_at->gt($report->created_at))
                <p class="mb-1"><strong>Terakhir Diperbarui:</strong> {{ $report->updated_at->format('d M Y H:i') }}</p>
                @endif
            </div>
        </div>
        
        <h6 class="fw-bold">Deskripsi:</h6>
        <p class="mb-4">{{ $report->description }}</p>
        
        @if($report->files->count() > 0)
        <h6 class="fw-bold">Lampiran:</h6>
        <div class="row">
            @foreach($report->files as $file)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        @if(in_array(strtolower(pathinfo($file->filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ Storage::url($file->path) }}" class="card-img-top" alt="{{ $file->filename }}" style="height: 150px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-file fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate">{{ $file->filename }}</p>
                            <a href="{{ Storage::url($file->path) }}" class="btn btn-sm btn-primary" target="_blank">Lihat</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        
        @if($user->isAdmin())
        <div class="mt-4">
            <h6 class="fw-bold">Update Status:</h6>
            <form action="{{ route('reports.update-status', $report) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in-progress" {{ $report->status == 'in-progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<!-- Comments Section -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Komentar ({{ $report->comments->count() }})</h5>
    </div>
    <div class="card-body">
        @if($report->comments->count() > 0)
            <div class="comments-list mb-4">
                @foreach($report->comments as $comment)
                    <div class="comment mb-3 p-3 {{ $comment->user->isAdmin() ? 'bg-light' : 'border-start border-4 border-primary' }}">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">
                                {{ $comment->user->name }}
                                @if($comment->user->isAdmin())
                                    <span class="badge bg-primary">Admin</span>
                                @endif
                            </h6>
                            <small class="text-muted">{{ $comment->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <p class="mb-0">{{ $comment->content }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted my-4">Belum ada komentar.</p>
        @endif
        
        <!-- Add Comment Form -->
        <form action="{{ route('reports.comments.store', $report) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Tambahkan Komentar</label>
                <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    </div>
</div>
@endsection
