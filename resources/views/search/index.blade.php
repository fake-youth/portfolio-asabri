<x-app-layout>
    <x-slot name="pageTitle">Hasil Pencarian</x-slot>

    <div class="container-fluid">
        <div class="mb-4">
            <h5 class="text-muted">
                <i class="fas fa-search me-2"></i>
                Menampilkan hasil untuk:
                @if($keyword) <strong class="text-dark">"{{ $keyword }}"</strong> @endif
                @if($startDate || $endDate)
                    <span class="badge bg-info text-dark ms-2">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'Awal' }} -
                        {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'Sekarang' }}
                    </span>
                @endif
            </h5>
            @if(empty($results))
                <p class="text-danger">Tidak ditemukan dokumen yang sesuai dengan kriteria pencarian.</p>
            @endif
        </div>

        @foreach($results as $type => $items)
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0 text-asabri fw-bold">{{ $type }} <span
                            class="badge bg-light text-dark ms-2">{{ count($items) }} ditemukan</span></h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Judul Dokumen</th>
                                    @if($type !== 'Kategori Dokumen')
                                        <th>Tanggal Laporan</th>
                                    @endif
                                    <th>Keterangan</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">{{ $item->title ?? $item->judul }}</div>
                                            @if($type === 'Kategori Dokumen')
                                                <small class="text-muted">Manager: {{ $item->manager }}</small>
                                            @endif
                                        </td>

                                        @if($type !== 'Kategori Dokumen')
                                            <td>
                                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                                {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}
                                            </td>
                                        @endif

                                        <td>
                                            @if($type === 'Kategori Dokumen')
                                                {{ Str::limit($item->description, 60) }}
                                            @elseif($type === 'Laporan Mingguan')
                                                Periode: {{ $item->periode_minggu }}
                                            @elseif($type === 'Laporan Bulanan')
                                                Bulan: {{ $item->periode_bulan }}
                                            @elseif($type === 'Laporan Tahunan')
                                                Tahun: {{ $item->tahun }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="text-end pe-4">
                                            @if($type === 'Kategori Dokumen')
                                                <a href="{{ route($item->route_name) }}" class="btn btn-sm btn-outline-primary">
                                                    Lihat Kategori
                                                </a>
                                            @else
                                                <div class="btn-group">
                                                    <a href="{{ $item->preview_url }}" target="_blank"
                                                        class="btn btn-sm btn-outline-info" title="Preview">
                                                        <i class="fas fa-eye"></i> Preview
                                                    </a>
                                                    <a href="{{ $item->download_url }}" class="btn btn-sm btn-asabri"
                                                        title="Download">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>