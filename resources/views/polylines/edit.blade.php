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
                        <h4 class="mb-0"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Data Polyline</h4>
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

                        <form action="{{ route('polylines.update', $polyline->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Polyline <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $polyline->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $polyline->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="length_km" class="form-label">Panjang (KM)</label>
                                <input type="number" step="any" class="form-control" id="length_km" name="length_km"
                                    value="{{ old('length_km', $polyline->length_km) }}">
                            </div>

                            <div class="mb-3">
                                <label for="geom" class="form-label">Geometry (WKT) <span class="text-danger">*</span></label>
                                <textarea class="form-control bg-light" id="geom" name="geom" rows="5" readonly required>{{ old('geom', $polyline->geom) }}</textarea>
                                <small class="text-muted">Ini adalah representasi WKT dari polyline di peta. Jangan diubah secara manual.</small>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Foto</label>
                                <input type="file" class="form-control" id="image" name="image"
                                    onchange="document.getElementById('preview-image').src = window.URL.createObjectURL(this.files[0])">
                                @if ($polyline->image)
                                    <p class="mt-2">Gambar saat ini:</p>
                                    <img src="{{ asset('storage/' . $polyline->image) }}" alt="Gambar Polyline Saat Ini" id="preview-image" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
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
                                    <i class="fa-solid fa-save me-2"></i> Perbarui Polyline
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
                @if ($polyline->image)
                    preview.src = "{{ asset('storage/' . $polyline->image) }}";
                    preview.style.display = 'block';
                @else
                    preview.style.display = 'none';
                @endif
            }
        });

        window.onload = function() {
            var preview = document.getElementById('preview-image');
            var imageInput = document.getElementById('image');
            @if (!$polyline->image)
                if (!imageInput.files || imageInput.files.length === 0) {
                    preview.style.display = 'none';
                }
            @endif
        };
    </script>
@endsection
