@extends('layouts.dashboard')

@section('title', 'Buat Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Buat Laporan Baru</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
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
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Pilih Kategori</option>
                    <option value="infrastruktur" {{ old('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                    <option value="lingkungan" {{ old('category') == 'lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                    <option value="pelayanan" {{ old('category') == 'pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                    <option value="keamanan" {{ old('category') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                    <option value="kesehatan" {{ old('category') == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                    <option value="pendidikan" {{ old('category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                    <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Contoh: Jl. Merdeka No. 123, Jakarta">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi Laporan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Jelaskan masalah yang ingin dilaporkan secara detail...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="files" class="form-label">Lampiran File (Opsional)</label>
                <input type="file" class="form-control" id="files" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <div class="form-text">Format yang didukung: JPG, PNG, PDF, DOC, DOCX. Maksimal 2MB per file.</div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('reports.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
