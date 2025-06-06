@extends('layouts.dashboard')

@section('title', 'Artikel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Artikel</h1>
    @if($user->isAdmin())
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Buat Artikel
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
        <form method="GET" action="{{ route('articles.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="pengumuman" {{ request('category') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                        <option value="update" {{ request('category') == 'update' ? 'selected' : '' }}>Update</option>
                        <option value="edukasi" {{ request('category') == 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                        <option value="kebijakan" {{ request('category') == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                        <option value="berita" {{ request('category') == 'berita' ? 'selected' : '' }}>Berita</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('articles.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Articles List -->
<div class="row">
    @forelse($articles as $article)
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title">{{ $article->title }}</h5>
                <div class="d-flex mb-3">
                    <span class="badge bg-info me-2">{{ ucfirst($article->category) }}</span>
                    <small class="text-muted">{{ $article->created_at->format('d M Y') }}</small>
                </div>
                <p class="card-text">{{ Str::limit($article->content, 150) }}</p>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-eye"></i> {{ $article->views }} views
                    </small>
                </div>
                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
            <h5>Belum ada artikel</h5>
            <p class="text-muted">{{ $user->isAdmin() ? 'Anda belum membuat artikel apapun.' : 'Belum ada artikel yang tersedia.' }}</p>
            @if($user->isAdmin())
            <a href="{{ route('articles.create') }}" class="btn btn-primary">Buat Artikel Pertama</a>
            @endif
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $articles->links() }}
</div>
@endsection
