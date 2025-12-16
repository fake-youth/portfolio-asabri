<x-app-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <x-stat-card icon="file-pdf" :value="$totalLaporan" label="Total Laporan" color="blue" />
        </div>

        <div class="col-md-3">
            <x-stat-card icon="chart-bar" :value="$totalFundFactSheets" label="Fund Fact Sheet" color="purple" />
        </div>

        <div class="col-md-3">
            <x-stat-card icon="calendar-week" :value="$totalLaporanMingguan" label="Laporan Mingguan" color="green" />
        </div>

        <div class="col-md-3">
            <x-stat-card icon="calendar" :value="$totalLaporanBulanan" label="Laporan Bulanan" color="orange" />
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="table-wrapper">
                <h5 class="mb-4" style="color: var(--asabri-navy);">
                    <i class="fas fa-chart-line"></i> Statistik Upload Laporan
                </h5>
                <canvas id="uploadChart" height="80"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div class="table-wrapper">
                <h5 class="mb-4" style="color: var(--asabri-navy);">
                    <i class="fas fa-pie-chart"></i> Distribusi Laporan
                </h5>
                <canvas id="distributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Latest Uploads & User Stats -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="table-wrapper">
                <h5 class="mb-4" style="color: var(--asabri-navy);">
                    <i class="fas fa-clock"></i> Upload Terbaru
                </h5>

                @if($latestUploads->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Upload</th>
                                    <th>Uploader</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestUploads as $upload)
                                    <tr>
                                        <td>{{ Str::limit($upload->judul, 40) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $upload->type }}</span>
                                        </td>
                                        <td>{{ formatTanggalIndonesia($upload->created_at) }}</td>
                                        <td>{{ $upload->uploader->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-4">Belum ada laporan yang diupload.</p>
                @endif
            </div>
        </div>

        @auth
            <div class="col-md-4">
                <div class="table-wrapper">
                    <h5 class="mb-4" style="color: var(--asabri-navy);">
                        <i class="fas fa-users"></i> Statistik User
                    </h5>

                    <div class="d-flex justify-content-between align-items-center mb-3 p-3"
                        style="background: var(--asabri-light); border-radius: 8px;">
                        <div>
                            <i class="fas fa-user text-secondary"></i>
                            <span class="ms-2">Users</span>
                        </div>
                        <h4 class="mb-0" style="color: var(--asabri-navy);">{{ $totalUsers }}</h4>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 p-3"
                        style="background: var(--asabri-light); border-radius: 8px;">
                        <div>
                            <i class="fas fa-user-shield text-warning"></i>
                            <span class="ms-2">Admins</span>
                        </div>
                        <h4 class="mb-0" style="color: var(--asabri-navy);">{{ $totalAdmins }}</h4>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3"
                        style="background: var(--asabri-light); border-radius: 8px;">
                        <div>
                            <i class="fas fa-crown text-danger"></i>
                            <span class="ms-2">Super Admins</span>
                        </div>
                        <h4 class="mb-0" style="color: var(--asabri-navy);">{{ $totalSuperAdmins }}</h4>
                    </div>

                    @if(isSuperAdmin())
                        <div class="mt-3 text-center">
                            <a href="{{ route('users.index') }}" class="btn btn-asabri btn-sm">
                                <i class="fas fa-users-cog"></i> Kelola User
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endauth
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Upload Chart
        const uploadCtx = document.getElementById('uploadChart').getContext('2d');
        new Chart(uploadCtx, {
            type: 'bar',
            data: {
                labels: ['Fund Fact Sheet', 'Mingguan', 'Bulanan', 'Tahunan'],
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: [
                        {{ $totalFundFactSheets }},
                        {{ $totalLaporanMingguan }},
                        {{ $totalLaporanBulanan }},
                        {{ $totalLaporanTahunan }}
                    ],
                    backgroundColor: [
                        'rgba(25, 118, 210, 0.8)',
                        'rgba(56, 142, 60, 0.8)',
                        'rgba(245, 124, 0, 0.8)',
                        'rgba(123, 31, 162, 0.8)'
                    ],
                    borderColor: [
                        'rgba(25, 118, 210, 1)',
                        'rgba(56, 142, 60, 1)',
                        'rgba(245, 124, 0, 1)',
                        'rgba(123, 31, 162, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Distribution Chart
        const distCtx = document.getElementById('distributionChart').getContext('2d');
        new Chart(distCtx, {
            type: 'doughnut',
            data: {
                labels: ['Fund Fact Sheet', 'Mingguan', 'Bulanan', 'Tahunan'],
                datasets: [{
                    data: [
                        {{ $totalFundFactSheets }},
                        {{ $totalLaporanMingguan }},
                        {{ $totalLaporanBulanan }},
                        {{ $totalLaporanTahunan }}
                    ],
                    backgroundColor: [
                        'rgba(25, 118, 210, 0.8)',
                        'rgba(56, 142, 60, 0.8)',
                        'rgba(245, 124, 0, 0.8)',
                        'rgba(123, 31, 162, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>