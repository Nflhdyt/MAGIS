@extends('layout.template')

@section('styles')
    {{-- Tambahkan CSS khusus jika diperlukan --}}
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Data Polygon</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('polygons.update', $polygon->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama_pemilik" class="form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_pemilik" name="nama_pemilik"
                                    value="{{ old('nama_pemilik', $polygon->nama_pemilik) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="luas_lahan" class="form-label">Luas Lahan <span class="text-danger">*</span></label>
                                <input type="number" step="any" class="form-control" id="luas_lahan" name="luas_lahan"
                                    value="{{ old('luas_lahan', $polygon->luas_lahan) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_kebun" class="form-label">Nama Kebun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_kebun" name="nama_kebun"
                                    value="{{ old('nama_kebun', $polygon->nama_kebun) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="nama_tanaman" class="form-label">Nama Tanaman <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_tanaman" name="nama_tanaman"
                                    value="{{ old('nama_tanaman', $polygon->nama_tanaman) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="jumlah_panen" class="form-label">Jumlah Panen per Musim <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="jumlah_panen" name="jumlah_panen"
                                    value="{{ old('jumlah_panen', $polygon->jumlah_panen) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="area_km" class="form-label">Luas Area (KM²)</label>
                                <input type="number" step="any" class="form-control" id="area_km" name="area_km"
                                    value="{{ old('area_km', $polygon->area_km) }}">
                            </div>

                            <div class="mb-3">
                                <label for="geom" class="form-label">Geometry (WKT) <span class="text-danger">*</span></label>
                                <textarea class="form-control bg-light" id="geom" name="geom" rows="5" readonly required>{{ old('geom', $polygon->geom) }}</textarea>
                                <small class="text-muted">Ini adalah representasi WKT dari poligon di peta. Jangan diubah secara manual.</small>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">
                                @if ($polygon->image)
                                    <p class="mt-2">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $polygon->image) }}" alt="Gambar Polygon Saat Ini" id="preview-image" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
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
                                    <i class="fa-solid fa-save me-2"></i> Perbarui Polygon
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
    <script>
        document.getElementById('image').addEventListener('change', function() {
            var preview = document.getElementById('preview-image');
            if (this.files && this.files[0]) {
                preview.src = URL.createObjectURL(this.files[0]);
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
                preview.src = '';
            }
        });

        document.getElementById('delete_image_checkbox')?.addEventListener('change', function() {
            var preview = document.getElementById('preview-image');
            if (this.checked) {
                preview.style.display = 'none';
            } else {
                @if ($polygon->image)
                    preview.src = "{{ asset('storage/' . $polygon->image) }}";
                    preview.style.display = 'block';
                @else
                    preview.style.display = 'none';
                @endif
            }
        });

        window.onload = function() {
            var preview = document.getElementById('preview-image');
            var imageInput = document.getElementById('image');
            @if (!$polygon->image)
                if (!imageInput.files || imageInput.files.length === 0) {
                    preview.style.display = 'none';
                }
            @endif
        };
    </script>
@endsection
