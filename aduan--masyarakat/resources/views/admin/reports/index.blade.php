@extends('layouts.admin')

@section('title', 'Kelola Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Laporan</h1>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari judul atau deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="Infrastruktur" {{ request('category') == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                        <option value="Keamanan" {{ request('category') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                        <option value="Lingkungan" {{ request('category') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                        <option value="Pelayanan" {{ request('category') == 'Pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                        <option value="Lainnya" {{ request('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.reports.bulk-update') }}" method="POST" id="bulkForm">
            @csrf
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h5 class="mb-0">Aksi Massal</h5>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select" required>
                        <option value="">Pilih Status</option>
                        <option value="pending">Pending</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning" id="bulkUpdateBtn" disabled>
                        <i class="fas fa-sync-alt"></i> Update Status
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Komentar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input report-checkbox" type="checkbox" name="report_ids[]" value="{{ $report->id }}" form="bulkForm">
                            </div>
                        </td>
                        <td>#{{ $report->id }}</td>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->user->name }}</td>
                        <td>{{ $report->category }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $report->status == 'pending' ? 'warning' : 
                                ($report->status == 'in-progress' ? 'info' : 
                                ($report->status == 'resolved' ? 'success' : 'danger')) 
                            }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td>{{ $report->created_at->format('d M Y') }}</td>
                        <td>{{ $report->comments->count() }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $report->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $report->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Laporan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus laporan <strong>{{ $report->title }}</strong>?</p>
                                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('admin.reports.delete', $report) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $reports->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const reportCheckboxes = document.querySelectorAll('.report-checkbox');
        const bulkUpdateBtn = document.getElementById('bulkUpdateBtn');
        
        // Select all functionality
        selectAll.addEventListener('change', function() {
            reportCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkButtonState();
        });
        
        // Individual checkbox change
        reportCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkButtonState();
                
                // Update "select all" checkbox
                const allChecked = Array.from(reportCheckboxes).every(c => c.checked);
                const someChecked = Array.from(reportCheckboxes).some(c => c.checked);
                
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            });
        });
        
        // Update bulk button state
        function updateBulkButtonState() {
            const checkedCount = Array.from(reportCheckboxes).filter(c => c.checked).length;
            bulkUpdateBtn.disabled = checkedCount === 0;
            
            if (checkedCount > 0) {
                bulkUpdateBtn.innerHTML = `<i class="fas fa-sync-alt"></i> Update ${checkedCount} Laporan`;
            } else {
                bulkUpdateBtn.innerHTML = `<i class="fas fa-sync-alt"></i> Update Status`;
            }
        }
    });
</script>
@endsection
