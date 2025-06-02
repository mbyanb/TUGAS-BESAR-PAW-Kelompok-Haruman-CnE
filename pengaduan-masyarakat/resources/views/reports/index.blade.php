<?php
// resources/views/reports/create.blade.php
?>
@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Buat Laporan Baru</h1>
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">
                    ← Kembali ke Dashboard
                </a>
            </div>

            <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Judul Laporan *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               placeholder="Contoh: Jalan Rusak di Jl. Sudirman"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Kategori *</label>
                        <select name="category" id="category" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Pilih kategori laporan</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}" 
                               placeholder="Contoh: Jl. Sudirman No. 123, Jakarta Pusat"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Masalah *</label>
                        <textarea name="description" id="description" rows="5" 
                                  placeholder="Jelaskan masalah secara detail..."
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Pendukung</label>
                        <div class="mt-1 border-2 border-dashed border-gray-300 rounded-lg p-6">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <label for="images" class="cursor-pointer">
                                        <span class="mt-2 block text-sm font-medium text-gray-900">
                                            Unggah foto untuk memperkuat laporan Anda
                                        </span>
                                        <span class="mt-1 block text-xs text-gray-500">
                                            Maksimal 5 foto, format JPG/PNG, ukuran maksimal 5MB per foto
                                        </span>
                                        <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                    </label>
                                    <button type="button" onclick="document.getElementById('images').click()" 
                                            class="mt-4 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Pilih Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Image Preview -->
                        <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4 hidden"></div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('dashboard') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Kirim Laporan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const preview = document.getElementById('image-preview');
    
    if (files.length > 0) {
        preview.classList.remove('hidden');
        preview.innerHTML = '';
        
        files.forEach((file, index) => {
            if (index < 5) { // Max 5 images
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg">
                        <button type="button" onclick="removeImage(${index})" 
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                            ×
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        preview.classList.add('hidden');
    }
});

function removeImage(index) {
    const input = document.getElementById('images');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    input.dispatchEvent(new Event('change'));
}
</script>
@endsection