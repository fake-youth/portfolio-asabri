<x-app-layout>
    <x-slot name="pageTitle">Kelola Kategori Dokumen</x-slot>

    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1" style="color: var(--asabri-navy);">
                    <i class="fas fa-folder-open"></i> Manajemen Kategori Dokumen
                </h5>
                <p class="text-muted mb-0 small">Kelola kartu kategori yang ditampilkan di halaman Overview</p>
            </div>

            <button class="btn btn-asabri" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">Image</th>
                            <th>Jenis Dokumen</th>
                            <th>Judul</th>
                            <th>Manager</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>
                                    <img src="{{ $category->image_url }}" alt="{{ $category->title }}"
                                        style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->getTypeLabel() }}</span>
                                </td>
                                <td><strong>{{ $category->title }}</strong></td>
                                <td>{{ $category->manager ?? '-' }}</td>
                                <td>{{ $category->published_at ? $category->published_at->format('d M Y') : '-' }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td>{{ $category->creator->name }}</td>
                                <td>
                                    <form action="{{ route('document-categories.toggle', $category->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-{{ $category->is_active ? 'secondary' : 'success' }}"
                                            title="{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>

                                    <button class="btn btn-sm btn-warning" onclick="editCategory({{ json_encode($category) }})">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form action="{{ route('document-categories.destroy', $category->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-muted py-5">Belum ada kategori. Tambahkan yang pertama!</p>
        @endif
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--asabri-navy); color: white;">
                    <h5 class="modal-title">Tambah Kategori Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('document-categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Dokumen *</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="fund_fact_sheet">Fund Fact Sheet</option>
                                    <option value="laporan_mingguan">Laporan Mingguan</option>
                                    <option value="laporan_bulanan">Laporan Bulanan</option>
                                    <option value="laporan_tahunan">Laporan Tahunan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Publish</label>
                                <input type="date" name="published_at" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Kategori *</label>
                            <input type="text" name="title" class="form-control"
                                placeholder="Contoh: Manulife Saham Andalan (MSA)" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Manager/Pengelola</label>
                            <input type="text" name="manager" class="form-control"
                                placeholder="Contoh: Manulife Asset Management">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" class="form-control" rows="2"
                                placeholder="Deskripsi singkat kategori..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Thumbnail (Opsional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 5MB. Rekomendasi ukuran:
                                300x180px</small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <strong>Info:</strong> Kategori akan ditampilkan sebagai kartu di halaman Overview sesuai
                            jenis dokumen yang dipilih.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-asabri">
                            <i class="fas fa-plus"></i> Tambah Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--asabri-navy); color: white;">
                    <h5 class="modal-title">Edit Kategori Dokumen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Dokumen *</label>
                                <select name="type" id="edit_type" class="form-control" required>
                                    <option value="fund_fact_sheet">Fund Fact Sheet</option>
                                    <option value="laporan_mingguan">Laporan Mingguan</option>
                                    <option value="laporan_bulanan">Laporan Bulanan</option>
                                    <option value="laporan_tahunan">Laporan Tahunan</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Publish</label>
                                <input type="date" name="published_at" id="edit_published_at" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Judul Kategori *</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Manager/Pengelola</label>
                            <input type="text" name="manager" id="edit_manager" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi (Opsional)</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Gambar Thumbnail (Kosongkan jika tidak diubah)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG. Maksimal 5MB</small>
                        </div>

                        <div id="current-image-preview"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-asabri">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCategory(category) {
            document.getElementById('editForm').action = "{{ url('document-categories') }}/" + category.id;
            document.getElementById('edit_type').value = category.type;
            document.getElementById('edit_title').value = category.title;
            document.getElementById('edit_manager').value = category.manager || '';
            document.getElementById('edit_description').value = category.description || '';

            // Format date for input type="date" (YYYY-MM-DD)
            if (category.published_at) {
                const dateObj = new Date(category.published_at);
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                document.getElementById('edit_published_at').value = `${year}-${month}-${day}`;
            } else {
                document.getElementById('edit_published_at').value = '';
            }

            // Show current image if exists
            const imagePreview = document.getElementById('current-image-preview');
            if (category.image_path) {
                imagePreview.innerHTML = `
                    <div class="alert alert-secondary">
                        <strong>Gambar saat ini:</strong><br>
                        <img src="${category.image_url}" alt="${category.title}" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">
                    </div>
                `;
            } else {
                imagePreview.innerHTML = '';
            }

            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
</x-app-layout>