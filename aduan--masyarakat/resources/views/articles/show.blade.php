@extends('layouts.dashboard')

@section('title', $article->title)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ $article->title }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('articles.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        @if($user->isAdmin())
        <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning ms-2">
            <i class="fas fa-edit"></i> Edit
        </a>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="d-flex mb-3">
                    <span class="badge bg-info me-2">{{ ucfirst($article->category) }}</span>
                    <small class="text-muted">{{ $article->created_at->format('d M Y H:i') }}</small>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <small class="text-muted me-3">
                        <i class="fas fa-user"></i> {{ $article->user->name }}
                    </small>
                    <small class="text-muted">
                        <i class="fas fa-eye"></i> {{ $article->views }} views
                    </small>
                </div>
            </div>
        </div>
        
        @if($article->relatedReport)
        <div class="alert alert-info">
            <h6><i class="fas fa-link"></i> Artikel ini terkait dengan laporan:</h6>
            <a href="{{ route('reports.show', $article->relatedReport) }}" class="text-decoration-none">
                {{ $article->relatedReport->title }}
            </a>
        </div>
        @endif
        
        <div class="article-content">
            {!! nl2br(e($article->content)) !!}
        </div>
    </div>
</div>
@endsection
