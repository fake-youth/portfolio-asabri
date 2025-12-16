<x-app-layout>
    <x-slot name="pageTitle">Laporan Mingguan</x-slot>

    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0" style="color: var(--asabri-navy);">
                <i class="fas fa-calendar-week"></i> Daftar Laporan Mingguan
            </h5>

            <div class="d-flex gap-2">
                <form action="{{ route('laporan.mingguan.index') }}" method="GET" class="d-flex gap-2">
                    <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Cari judul..."
                        value="{{ request('keyword') }}">
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    <button type="submit" class="btn btn-sm btn-asabri">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('keyword') || request('date'))
                        <a href="{{ route('laporan.mingguan.index') }}" class="btn btn-sm btn-secondary" title="Reset">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>

                @if(canManage())
                    <button class="btn btn-asabri" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload PDF
                    </button>
                @endif
            </div>
        </div>

        @if($laporans->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Laporan</th>
                            <th>Periode Minggu</th>
                            <th>Tanggal Laporan</th>
                            <th>Diupload Oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($laporans as $index => $laporan)
                            <tr>
                                <td>{{ $laporans->firstItem() + $index }}</td>
                                <td>{{ $laporan->judul }}</td>
                                <td>{{ $laporan->periode_minggu ?? '-' }}</td>
                                <td>{{ $laporan->tanggal_laporan->format('d/m/Y') }}</td>
                                <td>{{ $laporan->uploader->name }}</td>
                                <td>
                                    @auth
                                        <a href="{{ route('laporan.mingguan.download', $laporan->id) }}"
                                            class="btn btn-sm btn-primary" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('laporan.mingguan.preview', $laporan->id) }}"
                                            class="btn btn-sm btn-info text-white" target="_blank" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                            title="Login untuk mengunduh">
                                            <i class="fas fa-lock"></i>
                                        </a>
                                    @endauth

                                    @if(canManage())
                                        <button class="btn btn-sm btn-warning"
                                            onclick="editLaporan({{ $laporan->id }}, '{{ $laporan->judul }}', '{{ $laporan->tanggal_laporan->format('Y-m-d') }}', '{{ $laporan->periode_minggu }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('laporan.mingguan.destroy', $laporan->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $laporans->links() }}
            </div>
        @else
            <p class="text-center text-muted py-5">Belum ada laporan yang diupload.</p>
        @endif
    </div>

    @if(canManage())
        <div class="modal fade" id="uploadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: var(--asabri-navy); color: white;">
                        <h5 class="modal-title">Upload Laporan Mingguan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('laporan.mingguan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Laporan</label>
                                <input type="text" name="judul" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Periode Minggu</label>
                                <input type="text" name="periode_minggu" class="form-control"
                                    placeholder="Contoh: Minggu ke-1 Januari 2026">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Laporan</label>
                                <input type="date" name="tanggal_laporan" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File PDF</label>
                                <input type="file" name="file" class="form-control" accept=".pdf" required>
                                <small class="text-muted">Maksimal 20MB</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-asabri">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header" style="background: var(--asabri-navy); color: white;">
                        <h5 class="modal-title">Edit Laporan Mingguan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Judul Laporan</label>
                                <input type="text" name="judul" id="edit_judul" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Periode Minggu</label>
                                <input type="text" name="periode_minggu" id="edit_periode" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Laporan</label>
                                <input type="date" name="tanggal_laporan" id="edit_tanggal" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">File PDF (kosongkan jika tidak diubah)</label>
                                <input type="file" name="file" class="form-control" accept=".pdf">
                                <small class="text-muted">Maksimal 20MB</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-asabri">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function editLaporan(id, judul, tanggal, periode) {
                document.getElementById('editForm').action = "{{ url('laporan-mingguan') }}/" + id;
                document.getElementById('edit_judul').value = judul;
                document.getElementById('edit_tanggal').value = tanggal;
                document.getElementById('edit_periode').value = periode;
                new bootstrap.Modal(document.getElementById('editModal')).show();
            }
        </script>
    @endif
</x-app-layout>