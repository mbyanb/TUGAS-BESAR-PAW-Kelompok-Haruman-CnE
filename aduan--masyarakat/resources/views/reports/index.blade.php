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

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, deskripsi..." value="{{ request('search') }}">
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
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        @if($reports->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th> {{-- KOLOM BARU --}}
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
                        {{-- === BAGIAN THUMBNAIL BARU === --}}
                        <td>
                            @php
                                // Ambil file pertama yang merupakan gambar
                                $thumbnail = $report->files->firstWhere(fn($file) => Str::startsWith($file->type, 'image/'));
                            @endphp
                            
                            @if($thumbnail)
                                <a href="{{ Storage::url($thumbnail->path) }}" data-lightbox="report-thumb-{{ $report->id }}" data-title="{{ $report->title }}">
                                    <img src="{{ Storage::url($thumbnail->path) }}" alt="Thumbnail" style="width: 80px; height: 50px; object-fit: cover; border-radius: 5px;">
                                </a>
                            @else
                                <div class="text-center text-muted" style="width: 80px;">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('reports.show', $report) }}" class="text-decoration-none fw-bold">
                                {{ Str::limit($report->title, 40) }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($report->category) }}</span>
                        </td>
                        <td>
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
                        </td>
                        @if($user->isAdmin())
                        <td>{{ $report->user->name }}</td>
                        @endif
                        <td>{{ $report->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-outline-primary" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if(!$user->isAdmin() && $report->status == 'pending')
                            <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $reports->links() }}
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
            <h5>Belum ada laporan</h5>
            <p class="text-muted">{{ $user->isAdmin() ? 'Belum ada laporan yang masuk.' : 'Anda belum membuat laporan apapun.' }}</p>
            @if(!$user->isAdmin())
            <a href="{{ route('reports.create') }}" class="btn btn-primary mt-2">Buat Laporan Pertama Anda</a>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endpush