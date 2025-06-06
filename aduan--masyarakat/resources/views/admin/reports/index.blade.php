@extends('layouts.admin')

@section('title', 'Kelola Laporan')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Kelola Laporan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bulkUpdateModal">
            <i class="fas fa-edit"></i> Update Massal
        </button>
    </div>
</div>

<!-- Filter Form -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reports') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari laporan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="infrastruktur" {{ request('category') == 'infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                        <option value="lingkungan" {{ request('category') == 'lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                        <option value="pelayanan" {{ request('category') == 'pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                        <option value="keamanan" {{ request('category') == 'keamanan' ? 'selected' : '' }}>Keamanan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reports Table -->
<div class="card">
    <div class="card-body">
        <form id="bulkForm">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll">
                            </th>
                            <th>ID</th>
                            <th>Judul</th>
                            <th>Pelapor</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        <tr>
                            <td>
                                <input type="checkbox" name="report_ids[]" value="{{ $report->id }}" class="report-checkbox">
                            </td>
                            <td>#{{ $report->id }}</td>
                            <td>{{ Str::limit($report->title, 40) }}</td>
                            <td>{{ $report->user->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($report->category) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'in-progress' ? 'info' : ($report->status == 'resolved' ? 'success' : 'danger')) }}">
                                    {{ $report->status == 'pending' ? 'Menunggu' : ($report->status == 'in-progress' ? 'Diproses' : ($report->status == 'resolved' ? 'Selesai' : 'Ditolak')) }}
                                </span>
                            </td>
                            <td>{{ $report->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $report->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $report->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Status Modal -->
                        <div class="modal fade" id="statusModal{{ $report->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Status - {{ Str::limit($report->title, 30) }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('reports.update-status', $report) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                                    <option value="in-progress" {{ $report->status == 'in-progress' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                                    <option value="rejected" {{ $report->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

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
        </form>
        
        {{ $reports->links() }}
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Massal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.reports.bulk-update') }}" method="POST" id="bulkUpdateForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="bulk_status" class="form-label">Status Baru</label>
                        <select name="status" id="bulk_status" class="form-select" required>
                            <option value="pending">Menunggu</option>
                            <option value="in-progress">Diproses</option>
                            <option value="resolved">Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div id="selectedReports"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.report-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

document.getElementById('bulkUpdateForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
    const selectedReports = document.getElementById('selectedReports');
    
    selectedReports.innerHTML = '';
    
    if (checkedBoxes.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu laporan untuk diupdate!');
        return;
    }
    
    checkedBoxes.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'report_ids[]';
        input.value = checkbox.value;
        selectedReports.appendChild(input);
    });
});
</script>
@endsection
