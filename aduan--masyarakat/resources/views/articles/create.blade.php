@extends('layouts.dashboard')

@section('title', 'Buat Artikel')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buat Artikel Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('articles.index') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('articles.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="pengumuman" {{ old('category') == 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                    <option value="update" {{ old('category') == 'update' ? 'selected' : '' }}>Update</option>
                    <option value="edukasi" {{ old('category') == 'edukasi' ? 'selected' : '' }}>Edukasi</option>
                    <option value="kebijakan" {{ old('category') == 'kebijakan' ? 'selected' : '' }}>Kebijakan</option>
                    <option value="berita" {{ old('category') == 'berita' ? 'selected' : '' }}>Berita</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="related_report_id" class="form-label">Laporan Terkait (Opsional)</label>
                <select class="form-select" id="related_report_id" name="related_report_id">
                    <option value="">Tidak ada</option>
                    @foreach($reports as $report)
                        <option value="{{ $report->id }}" {{ old('related_report_id') == $report->id ? 'selected' : '' }}>
                            {{ $report->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Konten Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="10" required placeholder="Tulis konten artikel di sini...">{{ old('content') }}</textarea>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Artikel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
