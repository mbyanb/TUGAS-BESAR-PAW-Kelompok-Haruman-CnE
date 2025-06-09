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

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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
                            @if(Storage::disk('public')->exists($file->path))
                                <img src="{{ asset('storage/' . $file->path) }}" class="card-img-top" alt="{{ $file->filename }}" style="height: 150px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                    <small class="text-muted ms-2">File tidak ditemukan</small>
                                </div>
                            @endif
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                <i class="fas fa-file fa-3x text-secondary"></i>
                            </div>
                        @endif
                        <div class="card-body p-2">
                            <p class="card-text small text-truncate" title="{{ $file->filename }}">{{ $file->filename }}</p>
                            @if(Storage::disk('public')->exists($file->path))
                                <a href="{{ route('reports.download-file', $file->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                @if(in_array(strtolower(pathinfo($file->filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                    <a href="{{ asset('storage/' . $file->path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                @endif
                            @else
                                <span class="btn btn-sm btn-secondary disabled">File tidak tersedia</span>
                            @endif
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
                    <div class="comment mb-3 p-3 {{ $comment->user->isAdmin() ? 'bg-light' : 'border-start border-4 border-primary' }}" id="comment-{{ $comment->id }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">
                                        {{ $comment->user->name }}
                                        @if($comment->user->isAdmin())
                                            <span class="badge bg-primary">Admin</span>
                                        @endif
                                    </h6>
                                    <small class="text-muted">
                                        {{ $comment->created_at->format('d M Y H:i') }}
                                        @if($comment->updated_at->gt($comment->created_at))
                                            <span class="text-info">(diedit)</span>
                                        @endif
                                    </small>
                                </div>
                                
                                <!-- Comment Content (View Mode) -->
                                <div class="comment-content-{{ $comment->id }}">
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                                
                                <!-- Comment Edit Form (Hidden by default) -->
                                <div class="comment-edit-{{ $comment->id }}" style="display: none;">
                                    <form action="{{ route('comments.update', $comment) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-2">
                                            <textarea class="form-control" name="content" rows="3" required>{{ $comment->content }}</textarea>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-save"></i> Simpan
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelEdit({{ $comment->id }})">
                                                <i class="fas fa-times"></i> Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            @if($comment->user_id === Auth::id() || $user->isAdmin())
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if($comment->user_id === Auth::id())
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </li>
                                        @endif
                                        @if($comment->user_id === Auth::id() || $user->isAdmin())
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-muted my-4">Belum ada komentar.</p>
        @endif
        
        <!-- Add Comment Form -->
        <form action="{{ route('comments.store', $report) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Tambahkan Komentar</label>
                <textarea class="form-control" id="content" name="content" rows="3" required placeholder="Tulis komentar Anda..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Kirim Komentar
            </button>
        </form>
    </div>
</div>

<!-- Delete Comment Modal -->
<div class="modal fade" id="deleteCommentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteCommentForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function editComment(commentId) {
    // Hide content and show edit form
    document.querySelector('.comment-content-' + commentId).style.display = 'none';
    document.querySelector('.comment-edit-' + commentId).style.display = 'block';
}

function cancelEdit(commentId) {
    // Show content and hide edit form
    document.querySelector('.comment-content-' + commentId).style.display = 'block';
    document.querySelector('.comment-edit-' + commentId).style.display = 'none';
}

function deleteComment(commentId) {
    // Set the form action to delete the specific comment
    const form = document.getElementById('deleteCommentForm');
    form.action = '/comments/' + commentId;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('deleteCommentModal'));
    modal.show();
}
</script>
@endsection
