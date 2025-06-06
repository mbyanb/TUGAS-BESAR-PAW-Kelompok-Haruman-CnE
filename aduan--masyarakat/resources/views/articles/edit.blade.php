@extends('layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Artikel</h1>
        <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Artikel</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.update', $article) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $article->title) }}" 
                                   placeholder="Masukkan judul artikel" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih kategori artikel</option>
                                <option value="pengumuman" {{ old('category', $article->category) == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                                <option value="update" {{ old('category', $article->category) == 'update' ? 'selected' : '' }}>Update Laporan</option>
                                <option value="edukasi" {{ old('category', $article->category) == 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                                <option value="kebijakan" {{ old('category', $article->category) == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                                <option value="berita" {{ old('category', $article->category) == 'berita' ? 'selected' : '' }}>Berita</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="related_report_id" class="form-label">Laporan Terkait (Opsional)</label>
                            <select class="form-select @error('related_report_id') is-invalid @enderror" id="related_report_id" name="related_report_id">
                                <option value="">Pilih laporan terkait</option>
                                @foreach($reports as $report)
                                    <option value="{{ $report->id }}" {{ old('related_report_id', $article->related_report_id) == $report->id ? 'selected' : '' }}>
                                        {{ $report->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('related_report_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Konten Artikel <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="10" 
                                      placeholder="Tulis konten artikel di sini..." required>{{ old('content', $article->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection