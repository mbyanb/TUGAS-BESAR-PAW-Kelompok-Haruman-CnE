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

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $report->title }}</h5>
        @php
            $statusClass = [
                'pending' => 'warning', 'in-progress' => 'info', 'resolved' => 'success', 'rejected' => 'danger'
            ];
            $statusLabel = [
                'pending' => 'Menunggu', 'in-progress' => 'Diproses', 'resolved' => 'Selesai', 'rejected' => 'Ditolak'
            ];
        @endphp
        <span class="badge bg-{{ $statusClass[$report->status] ?? 'secondary' }}">
            {{ $statusLabel[$report->status] ?? 'N/A' }}
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
                @if($report->updated_at->gt($report->created_at))
                <p class="mb-1"><strong>Terakhir Diperbarui:</strong> {{ $report->updated_at->format('d M Y H:i') }}</p>
                @endif
            </div>
        </div>
        
        <h6 class="fw-bold">Deskripsi:</h6>
        <p class="mb-4">{{ $report->description }}</p>
        
        {{-- === BAGIAN LAMPIRAN YANG DISEMPURNAKAN === --}}
        @if($report->files->count() > 0)
        <h6 class="fw-bold">Lampiran:</h6>
        <div class="row">
            @foreach($report->files as $file)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        @if(Str::startsWith($file->type, 'image/'))
                            {{-- JIKA GAMBAR, BUAT AGAR BISA DIKLIK (LIGHTBOX) --}}
                            <a href="{{ Storage::url($file->path) }}" data-lightbox="report-gallery" data-title="{{ $file->filename }}">
                                <img src="{{ Storage::url($file->path) }}" class="card-img-top" alt="{{ $file->filename }}" style="height: 180px; object-fit: cover; cursor: pointer;">
                            </a>
                            <div class="card-body p-3 text-center">
                                <p class="card-text small text-truncate" title="{{ $file->filename }}">{{ $file->filename }}</p>
                                <a href="{{ Storage::url($file->path) }}" class="btn btn-sm btn-outline-primary" target="_blank">Lihat Full</a>
                            </div>
                        @else
                            {{-- JIKA DOKUMEN --}}
                            <div class="card-body d-flex flex-column justify-content-center align-items-center h-100 text-center p-3">
                                <i class="fas fa-file-alt fa-4x text-secondary mb-3"></i>
                                <p class="card-text small text-truncate" title="{{ $file->filename }}">{{ $file->filename }}</p>
                                <a href="{{ Storage::url($file->path) }}" class="btn btn-sm btn-primary mt-2" target="_blank">
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @endif
        
        @if($user->isAdmin())
        <div class="mt-4 pt-3 border-top">
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
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Komentar ({{ $report->comments->count() }})</h5>
    </div>
    <div class="card-body">
        @forelse($report->comments as $comment)
            <div class="comment mb-3 p-3 {{ $comment->user->isAdmin() ? 'bg-light rounded' : 'border-start border-4 border-primary' }}">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-1 fw-bold">
                        {{ $comment->user->name }}
                        @if($comment->user->isAdmin())
                            <span class="badge bg-primary ms-1">Admin</span>
                        @endif
                    </h6>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-0">{{ $comment->content }}</p>
            </div>
        @empty
            <p class="text-center text-muted my-4">Belum ada komentar.</p>
        @endforelse
        
        <hr>
        <form action="{{ route('reports.comments.store', $report) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Tambahkan Komentar</label>
                <textarea class="form-control" id="content" name="content" rows="3" required placeholder="Tulis komentar Anda..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    </div>
</div>

@endsection

@push('styles')
{{-- Tambahkan ini untuk Lightbox Gallery --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
{{-- Tambahkan ini untuk Lightbox Gallery --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush