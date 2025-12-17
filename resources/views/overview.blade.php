<x-app-layout>
    <x-slot name="pageTitle">Overview</x-slot>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="color: var(--asabri-navy); font-weight: 600;">
                <i class="fas fa-info-circle"></i> Informasi Portfolio
            </h4>
            <p class="text-muted mb-0">Daftar kategori dokumen dan laporan investasi</p>
        </div>

        @if(canManage())
            <a href="{{ route('document-categories.index') }}" class="btn btn-asabri">
                <i class="fas fa-cog"></i> Kelola Kategori
            </a>
        @endif
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="background-color: white; border-radius: 8px;">
                <div class="card-body py-3">
                    <form action="{{ route('overview') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-filter text-muted me-2"></i> <strong>Filter:</strong>
                        </div>
                        <div class="col-auto">
                            <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Bulan</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="day" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">Semua Tanggal</option>
                                @foreach(range(1, 31) as $d)
                                    <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>
                                        {{ $d }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if(request('year') || request('month') || request('day'))
                            <div class="col-auto">
                                <a href="{{ route('overview') }}"
                                    class="btn btn-sm btn-link text-danger text-decoration-none">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Fund Fact Sheet Section -->
    <div class="overview-section mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--asabri-navy); font-weight: 600;">
                <i class="fas fa-file-alt"></i> Fund Fact Sheet
            </h5>
            <button class="btn btn-sm btn-outline-primary toggle-collapse-btn" type="button" data-bs-toggle="collapse"
                data-bs-target="#fundFactSheetCollapse" aria-expanded="true" aria-controls="fundFactSheetCollapse">
                <i class="fas fa-chevron-up"></i> <span class="btn-text">Sembunyikan</span>
            </button>
        </div>

        <div class="collapse show" id="fundFactSheetCollapse">
            @if($fundFactSheetCategories->count() > 0)
                <div class="row g-3">
                    @foreach($fundFactSheetCategories as $category)
                        <div class="col-md-6 col-lg-4">
                            <div class="category-card">
                                <div class="category-image">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->title }}"
                                        onclick="showImagePreview('{{ $category->image_url }}', '{{ $category->title }}')"
                                        style="cursor: pointer;" title="Klik untuk memperbesar">
                                </div>
                                <div class="category-content">
                                    <h6 class="category-title">{{ $category->title }}</h6>
                                    @if($category->manager)
                                        <p class="category-manager">{{ $category->manager }}</p>
                                    @endif
                                    @if($category->description)
                                        <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('fundfactsheet.index') }}"
                                            class="btn btn-sm btn-outline-asabri w-100">
                                            <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada kategori Fund Fact Sheet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Laporan Mingguan Section -->
    <div class="overview-section mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--asabri-navy); font-weight: 600;">
                <i class="fas fa-calendar-week"></i> Laporan Mingguan
            </h5>
            <button class="btn btn-sm btn-outline-primary toggle-collapse-btn" type="button" data-bs-toggle="collapse"
                data-bs-target="#laporanMingguanCollapse" aria-expanded="true" aria-controls="laporanMingguanCollapse">
                <i class="fas fa-chevron-up"></i> <span class="btn-text">Sembunyikan</span>
            </button>
        </div>

        <div class="collapse show" id="laporanMingguanCollapse">
            @if($laporanMingguanCategories->count() > 0)
                <div class="row g-3">
                    @foreach($laporanMingguanCategories as $category)
                        <div class="col-md-6 col-lg-4">
                            <div class="category-card">
                                <div class="category-image">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->title }}"
                                        onclick="showImagePreview('{{ $category->image_url }}', '{{ $category->title }}')"
                                        style="cursor: pointer;" title="Klik untuk memperbesar">
                                </div>
                                <div class="category-content">
                                    <h6 class="category-title">{{ $category->title }}</h6>
                                    @if($category->manager)
                                        <p class="category-manager">{{ $category->manager }}</p>
                                    @endif
                                    @if($category->description)
                                        <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('laporan.mingguan.index') }}"
                                            class="btn btn-sm btn-outline-asabri w-100">
                                            <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada kategori Laporan Mingguan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Laporan Bulanan Section -->
    <div class="overview-section mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--asabri-navy); font-weight: 600;">
                <i class="fas fa-calendar-alt"></i> Laporan Bulanan
            </h5>
            <button class="btn btn-sm btn-outline-primary toggle-collapse-btn" type="button" data-bs-toggle="collapse"
                data-bs-target="#laporanBulananCollapse" aria-expanded="true" aria-controls="laporanBulananCollapse">
                <i class="fas fa-chevron-up"></i> <span class="btn-text">Sembunyikan</span>
            </button>
        </div>

        <div class="collapse show" id="laporanBulananCollapse">
            @if($laporanBulananCategories->count() > 0)
                <div class="row g-3">
                    @foreach($laporanBulananCategories as $category)
                        <div class="col-md-6 col-lg-4">
                            <div class="category-card">
                                <div class="category-image">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->title }}"
                                        onclick="showImagePreview('{{ $category->image_url }}', '{{ $category->title }}')"
                                        style="cursor: pointer;" title="Klik untuk memperbesar">
                                </div>
                                <div class="category-content">
                                    <h6 class="category-title">{{ $category->title }}</h6>
                                    @if($category->manager)
                                        <p class="category-manager">{{ $category->manager }}</p>
                                    @endif
                                    @if($category->description)
                                        <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('laporan.bulanan.index') }}"
                                            class="btn btn-sm btn-outline-asabri w-100">
                                            <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada kategori Laporan Bulanan.</p>
                </div>
            @endif
        </div>
    </div>

    <!--Laporan Tahunan Section -->
    <div class="overview-section mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0" style="color: var(--asabri-navy); font-weight: 600;">
                <i class="fas fa-calendar"></i> Laporan Tahunan
            </h5>
            <button class="btn btn-sm btn-outline-primary toggle-collapse-btn" type="button" data-bs-toggle="collapse"
                data-bs-target="#laporanTahunanCollapse" aria-expanded="true" aria-controls="laporanTahunanCollapse">
                <i class="fas fa-chevron-up"></i> <span class="btn-text">Sembunyikan</span>
            </button>
        </div>

        <div class="collapse show" id="laporanTahunanCollapse">
            @if($laporanTahunanCategories->count() > 0)
                <div class="row g-3">
                    @foreach($laporanTahunanCategories as $category)
                        <div class="col-md-6 col-lg-4">
                            <div class="category-card">
                                <div class="category-image">
                                    <img src="{{ $category->image_url }}" alt="{{ $category->title }}"
                                        onclick="showImagePreview('{{ $category->image_url }}', '{{ $category->title }}')"
                                        style="cursor: pointer;" title="Klik untuk memperbesar">
                                </div>
                                <div class="category-content">
                                    <h6 class="category-title">{{ $category->title }}</h6>
                                    @if($category->manager)
                                        <p class="category-manager">{{ $category->manager }}</p>
                                    @endif
                                    @if($category->description)
                                        <p class="category-description">{{ Str::limit($category->description, 80) }}</p>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('laporan.tahunan.index') }}"
                                            class="btn btn-sm btn-outline-asabri w-100">
                                            <i class="fas fa-eye me-1"></i> Lihat Dokumen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <p>Belum ada kategori Laporan Tahunan.</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .overview-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .category-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
            height: 100%;
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: var(--asabri-navy);
        }

        .category-image {
            width: 100%;
            height: 160px;
            background: #f5f6fa;
            overflow: hidden;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-content {
            padding: 15px;
        }

        .category-title {
            color: var(--asabri-navy);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .category-manager {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .category-description {
            color: #888;
            font-size: 13px;
            line-height: 1.4;
            margin-bottom: 0;
        }
    </style>
    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background: transparent; border: none;">
                <div class="modal-header border-0 p-0 mb-2">
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img src="" id="previewImage" class="img-fluid rounded shadow-lg"
                        style="max-height: 80vh; object-fit: contain;">
                    <h5 class="mt-3 text-white" id="previewTitle"></h5>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImagePreview(url, title) {
            document.getElementById('previewImage').src = url;
            document.getElementById('previewTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            const collapses = document.querySelectorAll('.collapse');

            collapses.forEach(collapse => {
                collapse.addEventListener('show.bs.collapse', function () {
                    const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
                    if (button) {
                        const icon = button.querySelector('i');
                        const text = button.querySelector('.btn-text');
                        icon.className = 'fas fa-chevron-up';
                        text.textContent = 'Sembunyikan';
                    }
                });

                collapse.addEventListener('hide.bs.collapse', function () {
                    const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
                    if (button) {
                        const icon = button.querySelector('i');
                        const text = button.querySelector('.btn-text');
                        icon.className = 'fas fa-chevron-down';
                        text.textContent = 'Tampilkan';
                    }
                });
            });
        });
    </script>
</x-app-layout>