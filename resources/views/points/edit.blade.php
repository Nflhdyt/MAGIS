{{-- resources/views/points/edit.blade.php --}}

@extends('layout.template') {{-- Asumsi Anda memiliki layout bernama 'layout.template' --}}

@section('styles')
    {{-- Tambahkan CSS khusus untuk halaman ini jika diperlukan --}}
    <style>
        .card-header {
            background-color: #ffc107; /* Warna kuning Bootstrap */
            color: white;
            font-weight: bold;
        }
        .form-label {
            font-weight: bold;
        }
        .img-thumbnail {
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Data Point</h4>
                    </div>
                    <div class="card-body">
                        {{-- Menampilkan pesan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Ada kesalahan dalam input Anda:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Form untuk mengedit data point --}}
                        <form action="{{ route('points.update', $point->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Penting: Gunakan method PUT untuk operasi update --}}

                            <div class="mb-3">
                                <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik"
                                    value="{{ old('nama_pemilik', $point->nama_pemilik) }}"
                                    placeholder="Masukkan nama pemilik" required>
                                @error('nama_pemilik')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="luas_lahan" class="form-label">Luas Lahan <span class="text-danger">*</span></label>
                                <input type="number" step="any" class="form-control" id="luas_lahan" name="luas_lahan"
                                    value="{{ old('luas_lahan', $point->luas_lahan) }}"
                                    placeholder="Masukkan luas lahan (contoh: 1.5)" required>
                                @error('luas_lahan')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_kebun" class="form-label">Nama Kebun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kebun" name="nama_kebun"
                                    value="{{ old('nama_kebun', $point->nama_kebun) }}"
                                    placeholder="Masukkan nama kebun" required>
                                @error('nama_kebun')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nama_tanaman" class="form-label">Nama Tanaman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_tanaman" name="nama_tanaman"
                                    value="{{ old('nama_tanaman', $point->nama_tanaman) }}"
                                    placeholder="Masukkan nama tanaman" required>
                                @error('nama_tanaman')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_panen" class="form-label">Jumlah Panen per Musim <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah_panen" name="jumlah_panen"
                                    value="{{ old('jumlah_panen', $point->jumlah_panen) }}"
                                    placeholder="Masukkan jumlah panen" required>
                                @error('jumlah_panen')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="geom" class="form-label">Geometry (WKT) <span class="text-danger">*</span></label>
                                <textarea class="form-control bg-light" id="geom" name="geom" rows="5" readonly required>{{ old('geom', $point->geom) }}</textarea>
                                <small class="text-muted">Ini adalah representasi WKT dari titik di peta. Jangan diubah secara manual.</small>
                                @error('geom')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror

                                @if ($point->image)
                                    <p class="mt-2">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $point->image) }}" alt="Gambar Point Saat Ini" id="preview-image" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="delete_image_checkbox" name="delete_image_checkbox" value="1">
                                        <label class="form-check-label" for="delete_image_checkbox">Hapus Gambar Ini</label>
                                    </div>
                                @else
                                    <img src="" alt="Preview Gambar" id="preview-image" class="img-thumbnail mt-2" style="max-width: 200px; max-height: 200px; object-fit: cover; display: none;">
                                @endif
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('map.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-arrow-left me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-warning text-white">
                                    <i class="fa-solid fa-save me-2"></i> Perbarui Point
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Tambahkan JavaScript khusus untuk halaman ini --}}
    <script>
        // Fungsi untuk menampilkan preview gambar saat diunggah
        document.getElementById('image').addEventListener('change', function() {
            var preview = document.getElementById('preview-image');
            if (this.files && this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
                preview.style.display = 'block';
            } else {
                // Sembunyikan preview jika tidak ada file yang dipilih
                // Ini akan mempertahankan gambar lama jika ada, kecuali checkbox delete_image_checkbox dicentang
                @if ($point->image)
                    preview.src = "{{ asset('storage/' . $point->image) }}";
                    preview.style.display = 'block';
                @else
                    preview.style.display = 'none';
                    preview.src = '';
                @endif
            }
        });

        // Logika untuk menghapus gambar yang sudah ada
        const deleteImageCheckbox = document.getElementById('delete_image_checkbox');
        if (deleteImageCheckbox) {
            deleteImageCheckbox.addEventListener('change', function() {
                var preview = document.getElementById('preview-image');
                if (this.checked) {
                    preview.style.display = 'none'; // Sembunyikan gambar preview saat ini
                } else {
                    // Tampilkan kembali jika checkbox di-uncheck dan ada gambar asli
                    @if ($point->image)
                        preview.src = "{{ asset('storage/' . $point->image) }}";
                        preview.style.display = 'block';
                    @else
                        preview.style.display = 'none'; // Tetap sembunyikan jika memang tidak ada gambar asli
                    @endif
                }
            });
        }


        // Sembunyikan preview jika tidak ada gambar asli dan tidak ada gambar baru yang dipilih saat pertama kali load
        window.onload = function() {
            var preview = document.getElementById('preview-image');
            var imageInput = document.getElementById('image');
            @if (!$point->image)
                if (!imageInput.files || imageInput.files.length === 0) {
                    preview.style.display = 'none';
                }
            @endif
        };
    </script>
@endsection
