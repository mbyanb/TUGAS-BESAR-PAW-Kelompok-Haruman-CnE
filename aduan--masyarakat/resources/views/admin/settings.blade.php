@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Pengaturan Sistem</h1>
</div>

<div class="row">
    <!-- System Information -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>PHP Version</th>
                            <td>{{ $systemInfo['php_version'] }}</td>
                        </tr>
                        <tr>
                            <th>Laravel Version</th>
                            <td>{{ $systemInfo['laravel_version'] }}</td>
                        </tr>
                        <tr>
                            <th>Database Size</th>
                            <td>{{ $systemInfo['database_size'] }}</td>
                        </tr>
                        <tr>
                            <th>Storage Size</th>
                            <td>{{ $systemInfo['storage_size'] }}</td>
                        </tr>
                        <tr>
                            <th>Server Time</th>
                            <td>{{ now()->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Pemeliharaan Sistem</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" id="clearCacheBtn">
                        <i class="fas fa-broom"></i> Bersihkan Cache
                    </button>
                    <button type="button" class="btn btn-warning" id="optimizeBtn">
                        <i class="fas fa-bolt"></i> Optimize Aplikasi
                    </button>
                    <button type="button" class="btn btn-info" id="storageBtn">
                        <i class="fas fa-link"></i> Refresh Storage Link
                    </button>
                    <button type="button" class="btn btn-danger" id="maintenanceBtn">
                        <i class="fas fa-tools"></i> Mode Pemeliharaan
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Backup Database</h5>
            </div>
            <div class="card-body">
                <p>Backup database terakhir: <strong>Belum pernah</strong></p>
                <div class="d-grid">
                    <button type="button" class="btn btn-success" id="backupBtn">
                        <i class="fas fa-database"></i> Backup Database
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Logs -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Log Sistem (10 Terakhir)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Pesan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ now()->subMinutes(5)->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge bg-success">INFO</span></td>
                        <td>User login: admin@siadu.com</td>
                    </tr>
                    <tr>
                        <td>{{ now()->subHours(1)->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge bg-warning">WARNING</span></td>
                        <td>Failed login attempt: unknown@example.com</td>
                    </tr>
                    <tr>
                        <td>{{ now()->subHours(2)->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge bg-info">INFO</span></td>
                        <td>New report created: #123</td>
                    </tr>
                    <tr>
                        <td>{{ now()->subHours(3)->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge bg-info">INFO</span></td>
                        <td>User registered: user@example.com</td>
                    </tr>
                    <tr>
                        <td>{{ now()->subHours(5)->format('Y-m-d H:i:s') }}</td>
                        <td><span class="badge bg-danger">ERROR</span></td>
                        <td>Database connection failed temporarily</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simulate maintenance actions with alerts
        document.getElementById('clearCacheBtn').addEventListener('click', function() {
            alert('Cache berhasil dibersihkan!');
        });
        
        document.getElementById('optimizeBtn').addEventListener('click', function() {
            alert('Aplikasi berhasil dioptimasi!');
        });
        
        document.getElementById('storageBtn').addEventListener('click', function() {
            alert('Storage link berhasil di-refresh!');
        });
        
        document.getElementById('maintenanceBtn').addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin mengaktifkan mode pemeliharaan?')) {
                alert('Mode pemeliharaan diaktifkan!');
            }
        });
        
        document.getElementById('backupBtn').addEventListener('click', function() {
            alert('Backup database berhasil dibuat!');
        });
    });
</script>
@endsection
