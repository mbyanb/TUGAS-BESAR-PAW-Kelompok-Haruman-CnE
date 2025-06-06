@extends('layouts.admin')

@section('title', 'Kelola Komentar')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Komentar</h1>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.comments') }}">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari komentar..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('admin.comments') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Comments Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Komentar</th>
                        <th>Pengguna</th>
                        <th>Laporan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comments as $comment)
                    <tr>
                        <td>#{{ $comment->id }}</td>
                        <td>{{ Str::limit($comment->content, 60) }}</td>
                        <td>
                            {{ $comment->user->name }}
                            @if($comment->user->isAdmin())
                                <span class="badge bg-primary">Admin</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reports.show', $comment->report) }}" class="text-decoration-none">
                                {{ Str::limit($comment->report->title, 30) }}
                            </a>
                        </td>
                        <td>{{ $comment->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $comment->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $comment->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal{{ $comment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Detail Komentar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Pengguna:</strong> {{ $comment->user->name }}</p>
                                    <p><strong>Laporan:</strong> {{ $comment->report->title }}</p>
                                    <p><strong>Tanggal:</strong> {{ $comment->created_at->format('d M Y H:i') }}</p>
                                    <p><strong>Komentar:</strong></p>
                                    <div class="border p-3 bg-light">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <a href="{{ route('reports.show', $comment->report) }}" class="btn btn-primary">Lihat Laporan</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $comment->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Komentar</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus komentar ini?</p>
                                    <div class="border p-3 bg-light">
                                        {{ Str::limit($comment->content, 100) }}
                                    </div>
                                    <p class="text-danger mt-2"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
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
                </tbody>
            </table>
        </div>
        
        {{ $comments->links() }}
    </div>
</div>
@endsection
