@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail User</h1>
    <div>
        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @if($targetUser->id !== $user->id)
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#roleModal">
            <i class="fas fa-user-cog"></i> Ubah Role
        </button>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash"></i> Hapus User
        </button>
        @endif
    </div>
</div>

<div class="row">
    <!-- User Profile -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-body text-center">
                @if($targetUser->profile_photo)
                    <img src="{{ Storage::url($targetUser->profile_photo) }}" alt="{{ $targetUser->name }}" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-5x text-white"></i>
                    </div>
                @endif
                <h4>{{ $targetUser->name }}</h4>
                <p class="text-muted">{{ $targetUser->email }}</p>
                <span class="badge bg-{{ $targetUser->role == 'admin' ? 'primary' : 'secondary' }} mb-3">
                    {{ ucfirst($targetUser->role) }}
                </span>
                <p class="mb-1"><strong>Bergabung:</strong> {{ $targetUser->created_at->format('d M Y') }}</p>
                <p><strong>Terakhir Login:</strong> {{ $targetUser->last_login ? $targetUser->last_login->format('d M Y H:i') : 'Belum pernah' }}</p>
            </div>
        </div>

        <!-- User Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik User</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h5>{{ $userStats['total_reports'] }}</h5>
                        <span class="text-muted">Total Laporan</span>
                    </div>
                    <div class="col-6 mb-3">
                        <h5>{{ $userStats['total_comments'] }}</h5>
                        <span class="text-muted">Total Komentar</span>
                    </div>
                    <div class="col-6">
                        <h5>{{ $userStats['pending_reports'] }}</h5>
                        <span class="text-muted">Laporan Pending</span>
                    </div>
                    <div class="col-6">
                        <h5>{{ $userStats['resolved_reports'] }}</h5>
                        <span class="text-muted">Laporan Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Reports -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Laporan User</h5>
            </div>
            <div class="card-body">
                @if($targetUser->reports->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($targetUser->reports as $report)
                                <tr>
                                    <td>#{{ $report->id }}</td>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ $report->category }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $report->status == 'pending' ? 'warning' : 
                                            ($report->status == 'in-progress' ? 'info' : 
                                            ($report->status == 'resolved' ? 'success' : 'danger')) 
                                        }}">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <p>User ini belum membuat laporan.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Comments -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Komentar User</h5>
            </div>
            <div class="card-body">
                @if($targetUser->comments->count() > 0)
                    <div class="list-group">
                        @foreach($targetUser->comments as $comment)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">
                                    <a href="{{ route('reports.show', $comment->report) }}">
                                        {{ $comment->report->title }}
                                    </a>
                                </h6>
                                <small>{{ $comment->created_at->format('d M Y H:i') }}</small>
                            </div>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCommentModal{{ $comment->id }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Delete Comment Modal -->
                        <div class="modal fade" id="deleteCommentModal{{ $comment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Komentar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
                                        <div class="alert alert-secondary">
                                            {{ $comment->content }}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.comments.delete', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p>User ini belum membuat komentar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Role - {{ $targetUser->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.update-role', $targetUser) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="user" {{ $targetUser->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $targetUser->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong>{{ $targetUser->name }}</strong>?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.users.delete', $targetUser) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
