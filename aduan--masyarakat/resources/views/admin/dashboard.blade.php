@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard Admin</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-download"></i> Export
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <i class="fas fa-calendar"></i> Minggu Ini
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Laporan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Artikel</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_articles'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Komentar</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_comments'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Report Status -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Status Laporan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h4 class="small font-weight-bold">Pending <span class="float-end">{{ $stats['pending_reports'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ ($stats['pending_reports'] / max($stats['total_reports'], 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <h4 class="small font-weight-bold">In Progress <span class="float-end">{{ $stats['in_progress_reports'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ ($stats['in_progress_reports'] / max($stats['total_reports'], 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <h4 class="small font-weight-bold">Resolved <span class="float-end">{{ $stats['resolved_reports'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ ($stats['resolved_reports'] / max($stats['total_reports'], 1)) * 100 }}%"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <h4 class="small font-weight-bold">Rejected <span class="float-end">{{ $stats['rejected_reports'] }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{ ($stats['rejected_reports'] / max($stats['total_reports'], 1)) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Stats -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Kategori Laporan</h6>
            </div>
            <div class="card-body">
                @foreach($category_stats as $category)
                <div class="mb-3">
                    <h4 class="small font-weight-bold">{{ $category->category }} <span class="float-end">{{ $category->count }}</span></h4>
                    <div class="progress mb-4">
                        <div class="progress-bar" role="progressbar" style="width: {{ ($category->count / max($stats['total_reports'], 1)) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Monthly Reports -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Bulanan</h6>
            </div>
            <div class="card-body">
                <div class="chart-bar">
                    <canvas id="monthlyReportsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Reports -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Terbaru</h6>
                <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Pelapor</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reports as $report)
                            <tr>
                                <td>#{{ $report->id }}</td>
                                <td>
                                    <a href="{{ route('reports.show', $report) }}">
                                        {{ $report->title }}
                                    </a>
                                </td>
                                <td>{{ $report->user->name }}</td>
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users & Comments -->
    <div class="col-lg-6">
        <!-- Recent Users -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">User Terbaru</h6>
                <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_users as $recent_user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $recent_user) }}">
                                        {{ $recent_user->name }}
                                    </a>
                                </td>
                                <td>{{ $recent_user->email }}</td>
                                <td>{{ $recent_user->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Comments -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Komentar Terbaru</h6>
                <a href="{{ route('admin.comments') }}" class="btn btn-sm btn-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Komentar</th>
                                <th>Laporan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_comments as $comment)
                            <tr>
                                <td>{{ $comment->user->name }}</td>
                                <td>
                                    <div style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ $comment->content }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('reports.show', $comment->report) }}#comment-{{ $comment->id }}">
                                        #{{ $comment->report->id }}
                                    </a>
                                </td>
                                <td>{{ $comment->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Reports Chart
        const monthlyData = @json($monthly_reports);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        const labels = [];
        const data = [];
        
        for (let i = 1; i <= 12; i++) {
            labels.push(months[i-1]);
            
            const found = monthlyData.find(item => item.month === i);
            data.push(found ? found.count : 0);
        }
        
        const ctx = document.getElementById('monthlyReportsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: data,
                    backgroundColor: 'rgba(78, 115, 223, 0.5)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
