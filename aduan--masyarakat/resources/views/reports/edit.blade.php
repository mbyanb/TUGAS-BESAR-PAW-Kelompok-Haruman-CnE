@extends('layouts.app')

@section('title', 'Edit Laporan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Laporan</h1>
        <a href="{{ route('reports.show', $report) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Laporan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.update', $report) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $report->title) }}" 
                                   placeholder="Masukkan judul laporan yang jelas" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih kategori laporan</option>
                                <option value="infrastruktur" {{ old('category', $report->category) == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="lingkungan" {{ old('category', $report->category) == 'lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                <option value="pelayanan" {{ old('category', $report->category) == 'pelayanan' ? 'selected' : '' }}>Pelayanan Publik</option>
                                <option value="keamanan" {{ old('category', $report->category) == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="kesehatan" {{ old('category', $report->category) == 'kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="pendidikan" {{ old('category', $report->category) == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="lainnya" {{ old('category', $report->category) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location', $report->location) }}" 
                                   placeholder="Masukkan lokasi kejadian">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Laporan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" 
                                      placeholder="Jelaskan detail laporan Anda..." required>{{ old('description', $report->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(Auth::user()->isAdmin())
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="in-progress" {{ $report->status == 'in-progress' ? 'selected' : '' }}>Diproses</option>
                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                        @endif

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('reports.show', $report) }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
               