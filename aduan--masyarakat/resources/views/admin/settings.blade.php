@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Sistem</h1>
</div>

<!-- System Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info-circle"></i> Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td>{{ $systemInfo['php_version'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Laravel Version:</strong></td>
                        <td>{{ $systemInfo['laravel_version'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Database Size:</strong></td>
                        <td>{{ $systemInfo['database_size'] }}</td>
                    </tr>
                    <tr>
                        <td><strong>Storage Size:</strong></td>
                        <td>{{ $systemInfo['storage_size'] }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tools"></i> Maintenance Tools</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="clearCache()">
                        <i class="fas fa-broom"></i> Clear Cache
                    </button>
                    <button class="btn btn-outline-warning" onclick="optimizeApp()">
                        <i class="fas fa-rocket"></i> Optimize Application
                    </button>
                    <button class="btn btn-outline-info" onclick="runMigrations()">
                        <i class="fas fa-database"></i> Run Migrations
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Settings -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-cog"></i> Pengaturan Aplikasi</h5>
    </div>
    <div class="card-body">
        <form>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="app_name" class="form-label">Nama Aplikasi</label>
                        <input type="text" class="form-control" id="app_name" value="SiADU">
                    </div>
                    <div class="mb-3">
                        <label for="app_url" class="form-label">URL Aplikasi</label>
                        <input type="url" class="form-control" id="app_url" value="{{ config('app.url') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="timezone">
                            <option value="Asia/Jakarta" selected>Asia/Jakarta</option>
                            <option value="Asia/Makassar">Asia/Makassar</option>
                            <option value="Asia/Jayapura">Asia/Jayapura</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="locale" class="form-label">Bahasa</label>
                        <select class="form-select" id="locale">
                            <option value="id" selected>Bahasa Indonesia</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function clearCache() {
    if (confirm('Apakah Anda yakin ingin membersihkan cache?')) {
        // In a real application, this would make an AJAX call to clear cache
        alert('Cache berhasil dibersihkan!');
    }
}

function optimizeApp() {
    if (confirm('Apakah Anda yakin ingin mengoptimasi aplikasi?')) {
        // In a real application, this would make an AJAX call to optimize
        alert('Aplikasi berhasil dioptimasi!');
    }
}

function runMigrations() {
    if (confirm('Apakah Anda yakin ingin menjalankan migrasi database?')) {
        // In a real application, this would make an AJAX call to run migrations
        alert('Migrasi database berhasil dijalankan!');
    }
}
</script>
@endsection
