{{-- resources/views/map.blade.php --}}

@extends('layout.template') {{-- Asumsi Anda memiliki layout bernama 'layout.template' --}}

@section('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    {{-- Leaflet Draw CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <style>
        #map {
            height: calc(100vh - 56px); /* Mengisi tinggi layar kecuali navbar (jika ada) */
            width: 100%;
        }

        /* Gaya untuk popup info */
        .info-popup {
            max-height: 250px; /* Batasi tinggi popup agar tidak terlalu panjang */
            overflow-y: auto; /* Aktifkan scroll jika konten melebihi max-height */
        }

        .info-popup h6 {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .info-popup p {
            margin-bottom: 3px;
        }

        .info-popup img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-top: 10px;
            border-radius: 5px;
        }

        /* Gaya untuk tombol edit/delete di popup */
        .info-popup .btn-group {
            margin-top: 10px;
            display: flex; /* Menggunakan flexbox untuk penempatan tombol */
            gap: 5px; /* Jarak antar tombol */
        }

        /* Gaya opsional untuk highlight kecamatan yang terpotong */
        .kecamatan-highlight {
            fillColor: #00BFFF !important; /* Deep Sky Blue */
            fillOpacity: 0.6 !important;
            color: #00008B !important; /* Dark Blue */
            weight: 3 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
                <div class="card-body">
                {{-- Alert untuk notifikasi sukses/error --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div id="map"></div>
            </div>
        </div>
    </div>

{{-- Modal Tambah Data Point --}}
    <div class="modal fade" id="CreatePointModal" tabindex="-1" aria-labelledby="CreatePointModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreatePointModalLabel">Tambah Data Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('points.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_pemilik_point" class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control" id="nama_pemilik_point" name="nama_pemilik" required>
                        </div>
                        <div class="mb-3">
                            <label for="luas_lahan_point" class="form-label">Luas Lahan</label>
                            <input type="number" step="any" class="form-control" id="luas_lahan_point" name="luas_lahan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kebun_point" class="form-label">Nama Kebun</label>
                            <input type="text" class="form-control" id="nama_kebun_point" name="nama_kebun" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_tanaman_point" class="form-label">Nama Tanaman</label>
                            <input type="text" class="form-control" id="nama_tanaman_point" name="nama_tanaman" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_panen_point" class="form-label">Jumlah Panen per Musim</label>
                            <input type="number" class="form-control" id="jumlah_panen_point" name="jumlah_panen" required>
                        </div>
                        <div class="mb-3">
                            <label for="image_point" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail mt-2" width="150">
                        </div>
                        <input type="hidden" id="geom_point" name="geom" required> {{-- Nama input sesuai proyek Anda --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Point</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Data Polyline --}}
    <div class="modal fade" id="CreatePolylineModal" tabindex="-1" aria-labelledby="CreatePolylineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreatePolylineModalLabel">Tambah Data Polyline</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('polylines.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="name_polyline" class="form-label">Nama Polyline</label>
                            <input type="text" class="form-control" id="name_polyline" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description_polyline" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description_polyline" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="length_km_polyline" class="form-label">Panjang (KM)</label>
                            <input type="number" step="any" class="form-control" id="length_km_polyline" name="length_km" readonly>
                            <small class="text-muted">Dihitung otomatis dari geometri.</small>
                        </div>
                        <div class="mb-3">
                            <label for="image_polyline" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image_polyline" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail mt-2" width="150">
                        </div>
                        <input type="hidden" id="geom_polyline" name="geom" required> {{-- Nama input sesuai proyek Anda --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Polyline</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Data Polygon --}}
    <div class="modal fade" id="CreatePolygonModal" tabindex="-1" aria-labelledby="CreatePolygonModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CreatePolygonModalLabel">Tambah Data Polygon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('polygons.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_pemilik_polygon" class="form-label">Nama Pemilik</label>
                            <input type="text" class="form-control" id="nama_pemilik_polygon" name="nama_pemilik" required>
                        </div>
                        <div class="mb-3">
                            <label for="luas_lahan_polygon" class="form-label">Luas Lahan</label>
                            <input type="number" step="any" class="form-control" id="luas_lahan_polygon" name="luas_lahan" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_kebun_polygon" class="form-label">Nama Kebun</label>
                            <input type="text" class="form-control" id="nama_kebun_polygon" name="nama_kebun" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_tanaman_polygon" class="form-label">Nama Tanaman</label>
                            <input type="text" class="form-control" id="nama_tanaman_polygon" name="nama_tanaman" required>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_panen_polygon" class="form-label">Jumlah Panen per Musim</label>
                            <input type="number" class="form-control" id="jumlah_panen_polygon" name="jumlah_panen" required>
                        </div>
                            <div class="mb-3">
                            <label for="area_km_polygon" class="form-label">Luas Area (KM²)</label>
                            <input type="number" step="any" class="form-control" id="area_km_polygon" name="area_km" readonly>
                            <small class="text-muted">Dihitung otomatis dari geometri.</small>
                        </div>
                        <div class="mb-3">
                            <label for="image_polygon" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail mt-2" width="150">
                        </div>
                        <input type="hidden" id="geom_polygon" name="geom" required> {{-- Nama input sesuai proyek Anda --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Polygon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    {{-- Leaflet Draw JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    {{-- jQuery (untuk interaksi modal dan AJAX) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Terraformer & WKT (Ini yang kunci!) --}}
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    {{-- Turf.js untuk perhitungan (luas, panjang) dan operasi spasial --}}
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>


    <script>
        // Inisialisasi peta Leaflet
        var map = L.map('map').setView([-6.3851814723053515, 108.24951605927082], 10); // Koordinat Yogyakarta

        // Basemap (OpenStreetMap)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // --- PENTING: Pengaturan Urutan Layer Group ---
        // 1. staticGeoJsonLayerGroup: Untuk kecamatan.geojson. Ditambahkan PERTAMA, jadi akan berada di lapisan paling bawah.
        var staticGeoJsonLayerGroup = L.featureGroup().addTo(map);

        // 2. dbFeaturesLayerGroup: Untuk fitur dari database (Point, Polyline, Polygon). Ditambahkan KEDUA, jadi akan di atas staticGeoJsonLayerGroup.
        var dbFeaturesLayerGroup = L.featureGroup().addTo(map);

        // 3. drawnItems: Untuk fitur yang digambar atau diedit. Ditambahkan KETIGA/TERAKHIR, jadi akan di lapisan paling atas.
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // --- Variabel Global untuk Data Kecamatan GeoJSON ---
        var kecamatanGeoJsonData = null; // Akan diisi setelah kecamatan.geojson dimuat

        // Kontrol gambar Leaflet Draw
        var drawControl = new L.Control.Draw({
            edit: {
                featureGroup: drawnItems, // Edit akan bekerja pada layer di drawnItems
                poly: {
                    allowIntersection: false
                }
            },
            draw: {
                polygon: {
                    allowIntersection: false,
                    showArea: true
                },
                polyline: {
                    showLength: true
                },
                circle: false,
                rectangle: true,
                marker: true,
                circlemarker: false,
            }
        });
        map.addControl(drawControl);

        // --- Event Listener untuk Leaflet Draw ---
        map.on('draw:created', function(event) {
            var type = event.layerType,
                layer = event.layer;

            var geojson = layer.toGeoJSON();
            var wkt = Terraformer.geojsonToWKT(geojson.geometry);

            console.log("Created Type:", type);
            console.log("Created GeoJSON:", geojson);
            console.log("Created WKT:", wkt);

            if (type === 'marker') {
                $('#geom_point').val(wkt);
                var pointModal = new bootstrap.Modal(document.getElementById('CreatePointModal'));
                pointModal.show();
            } else if (type === 'polyline') {
                $('#geom_polyline').val(wkt);
                var length = turf.length(geojson, {units: 'kilometers'});
                $('#length_km_polyline').val(length.toFixed(3));
                var polylineModal = new bootstrap.Modal(document.getElementById('CreatePolylineModal'));
                polylineModal.show();
            } else if (type === 'polygon' || type === 'rectangle') {
                $('#geom_polygon').val(wkt);
                var area = turf.area(geojson) / 1000000;
                $('#area_km_polygon').val(area.toFixed(3));
                var polygonModal = new bootstrap.Modal(document.getElementById('CreatePolygonModal'));
                polygonModal.show();
            }

            drawnItems.addLayer(layer); // Tambahkan layer ke drawnItems agar bisa diedit dan juga terlihat di atas
        });

        // Event listener untuk pengeditan fitur
        map.on('draw:edited', function(event) {
            event.layers.eachLayer(function(layer) {
                var geojson = layer.toGeoJSON();
                var wkt = Terraformer.geojsonToWKT(geojson.geometry);

                var featureType;
                if (layer.feature && layer.feature.properties && layer.feature.properties.feature_type) {
                    featureType = layer.feature.properties.feature_type.toLowerCase() + 's'; // Tambahkan 's' untuk nama route: points, polylines, polygons
                } else {
                    // Fallback jika feature_type tidak ada (misal layer baru yang diedit tanpa disimpan)
                    if (layer instanceof L.Marker) {
                        featureType = 'points';
                    } else if (layer instanceof L.Polyline) {
                        featureType = 'polylines';
                    } else if (layer instanceof L.Polygon) {
                        featureType = 'polygons';
                    }
                }

                if (featureType) {
                    var id = layer.feature.properties.id; // ID harus ada dari data DB
                    var redirectUrl = `/${featureType}/${id}/edit`;

                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = redirectUrl;

                    var csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    var methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'GET'; // Mengirim GET request untuk halaman edit
                    form.appendChild(methodInput);

                    var geomInput = document.createElement('input');
                    geomInput.type = 'hidden';
                    geomInput.name = 'geom';
                    geomInput.value = wkt;
                    form.appendChild(geomInput);

                    if (featureType === 'polylines') {
                        var length = turf.length(geojson, {units: 'kilometers'});
                        var lengthInput = document.createElement('input');
                        lengthInput.type = 'hidden';
                        lengthInput.name = 'length_km';
                        lengthInput.value = length.toFixed(3);
                        form.appendChild(lengthInput);
                    } else if (featureType === 'polygons') {
                        var area = turf.area(geojson) / 1000000;
                        var areaInput = document.createElement('input');
                        areaInput.type = 'hidden';
                        areaInput.name = 'area_km';
                        areaInput.value = area.toFixed(3);
                        form.appendChild(areaInput);
                    }

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });

        // --- Fungsi untuk memuat dan menampilkan data dari API dan GeoJSON statis ---
        async function loadFeatures() {
            try {
                // Bersihkan semua layer dari dbFeaturesLayerGroup agar tidak duplikasi saat load ulang
                dbFeaturesLayerGroup.clearLayers();
                // Bersihkan juga drawnItems karena layer dari DB akan ditambahkan kembali ke drawnItems
                drawnItems.clearLayers();


                // 1. Muat data dari API (Points, Polylines, Polygons dari database)
                const apiResponse = await fetch('{{ route('api.all_features') }}');
                const apiData = await apiResponse.json();

                L.geoJson(apiData, {
                    onEachFeature: function(feature, layer) {
                        // Pastikan properti feature ada, terutama 'id' dan 'feature_type'
                        layer.feature = layer.feature || {};
                        layer.feature.properties = feature.properties; // Pastikan properti tetap ada
                        // Tambahkan ID ke properti layer agar bisa diakses saat edit/delete
                        layer.feature.properties.id = feature.properties.id;
                        layer.feature.properties.feature_type = feature.properties.feature_type;


                        var popupContent = '<div class="info-popup">';
                        if (feature.properties.feature_type === 'Point') {
                            popupContent += '<h6>Nama Pemilik:</h6><p>' + (feature.properties.nama_pemilik || '-') + '</p>';
                            popupContent += '<h6>Luas Lahan:</h6><p>' + (feature.properties.luas_lahan || '-') + ' Ha</p>';
                            popupContent += '<h6>Nama Kebun:</h6><p>' + (feature.properties.nama_kebun || '-') + '</p>';
                            popupContent += '<h6>Nama Tanaman:</h6><p>' + (feature.properties.nama_tanaman || '-') + '</p>';
                            popupContent += '<h6>Jumlah Panen:</h6><p>' + (feature.properties.jumlah_panen || '-') + ' per musim</p>';
                        } else if (feature.properties.feature_type === 'Polyline') {
                            popupContent += '<h6>Nama Polyline:</h6><p>' + (feature.properties.name || '-') + '</p>';
                            popupContent += '<h6>Deskripsi:</h6><p>' + (feature.properties.description || '-') + '</p>';
                            popupContent += '<h6>Panjang:</h6><p>' + (feature.properties.length_km ? feature.properties.length_km.toFixed(3) + ' KM' : '-') + '</p>';
                        } else if (feature.properties.feature_type === 'Polygon') {
                            popupContent += '<h6>Nama Pemilik:</h6><p>' + (feature.properties.nama_pemilik || '-') + '</p>';
                            popupContent += '<h6>Luas Lahan:</h6><p>' + (feature.properties.luas_lahan || '-') + ' Ha</p>';
                            popupContent += '<h6>Nama Kebun:</h6><p>' + (feature.properties.nama_kebun || '-') + '</p>';
                            popupContent += '<h6>Nama Tanaman:</h6><p>' + (feature.properties.nama_tanaman || '-') + '</p>';
                            popupContent += '<h6>Jumlah Panen:</h6><p>' + (feature.properties.jumlah_panen || '-') + ' per musim</p>';
                            popupContent += '<h6>Luas Area:</h6><p>' + (feature.properties.area_km ? feature.properties.area_km.toFixed(3) + ' KM²' : '-') + '</p>';

                            // --- LOGIKA UNTUK DETEKSI INTERSEKSI DENGAN KECAMATAN.GEOJSON ---
                            if (kecamatanGeoJsonData) { // Pastikan data kecamatan sudah dimuat
                                let intersectingKecamatanNames = [];
                                // Iterasi setiap fitur di kecamatanGeoJsonData
                                turf.featureEach(kecamatanGeoJsonData, function(kecamatanFeature) {
                                    // Periksa apakah poligon saat ini berpotongan dengan poligon kecamatan
                                    if (turf.booleanIntersects(feature, kecamatanFeature)) {
                                        if (kecamatanFeature.properties && kecamatanFeature.properties.nama) {
                                            intersectingKecamatanNames.push(kecamatanFeature.properties.nama);
                                        } else {
                                            intersectingKecamatanNames.push('Nama Tidak Diketahui');
                                        }
                                    }
                                });

                                if (intersectingKecamatanNames.length > 0) {
                                    popupContent += '<hr><h6>Berpotongan dengan Kecamatan:</h6>';
                                    popupContent += '<ul>';
                                    intersectingKecamatanNames.forEach(name => {
                                        popupContent += '<li>' + name + '</li>';
                                    });
                                    popupContent += '</ul>';

                                    // --- Logika untuk Highlight Kecamatan saat popup dibuka/ditutup ---
                                    layer.on('popupopen', function() {
                                        staticGeoJsonLayerGroup.eachLayer(function(kecLayer) {
                                            if (kecLayer.feature && kecLayer.feature.properties && intersectingKecamatanNames.includes(kecLayer.feature.properties.nama)) {
                                                // Hanya tambahkan kelas jika layer adalah polygon dan berpotongan
                                                if (kecLayer instanceof L.Polygon) {
                                                    kecLayer.addClass('kecamatan-highlight'); // Tambahkan kelas CSS
                                                }
                                            }
                                        });
                                    });
                                    layer.on('popupclose', function() {
                                        staticGeoJsonLayerGroup.eachLayer(function(kecLayer) {
                                            if (kecLayer.feature && kecLayer.feature.properties && intersectingKecamatanNames.includes(kecLayer.feature.properties.nama)) {
                                                // Hanya hapus kelas jika layer adalah polygon dan berpotongan
                                                if (kecLayer instanceof L.Polygon) {
                                                    kecLayer.removeClass('kecamatan-highlight'); // Hapus kelas CSS
                                                }
                                            }
                                        });
                                    });
                                    // --- Akhir Logika Highlight ---
                                }
                            }
                            // --- AKHIR LOGIKA INTERSEKSI ---
                        }

                        // Ini adalah bagian yang menambahkan gambar, berlaku untuk semua jenis fitur
                        if (feature.properties.image) {
                            popupContent += '<img src="{{ asset('storage') }}/' + feature.properties.image + '" alt="Gambar Fitur">';
                        }
                        popupContent += '<p class="mt-2">Dibuat oleh: ' + (feature.properties.user_created || 'N/A') + '</p>';

                        popupContent += `
                            <div class="btn-group">
                                <a href="/${feature.properties.feature_type.toLowerCase()}s/${feature.properties.id}/edit" class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-edit me-1"></i> Edit
                                </a>
                                <form action="/${feature.properties.feature_type.toLowerCase()}s/${feature.properties.id}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash-alt me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        `;
                        popupContent += '</div>';

                        layer.bindPopup(popupContent);
                        // Tambahkan fitur dari database ke dbFeaturesLayerGroup (untuk tampilan)
                        dbFeaturesLayerGroup.addLayer(layer);
                        // Tambahkan juga ke drawnItems agar bisa diedit oleh Leaflet.draw
                        // Ini penting karena Leaflet.draw Control 'edit' hanya bekerja pada layer di featureGroup yang ditentukan
                        drawnItems.addLayer(layer);
                    },
                    style: function(feature) {
                        switch (feature.geometry.type) {
                            case 'Point':
                                return {
                                    // Default marker style, can be replaced with custom icon
                                };
                            case 'LineString':
                                return {
                                    color: 'red',
                                    weight: 4,
                                    opacity: 0.7
                                };
                            case 'Polygon':
                                return {
                                    color: 'green',
                                    weight: 2,
                                    fillColor: 'lightgreen',
                                    fillOpacity: 0.5
                                };
                        }
                    },
                    pointToLayer: function(feature, latlng) {
                        // Default Leaflet marker
                        return L.marker(latlng);
                    }
                });

                // Opsional: Pastikan dbFeaturesLayerGroup di depan jika ada layer lain yang ditambahkan setelahnya
                // Ini akan memaksa layer ini ke bagian depan tumpukan.
                dbFeaturesLayerGroup.bringToFront();
                drawnItems.bringToFront(); // Pastikan juga drawnItems tetap di paling depan
            } catch (error) {
                console.error('Error loading API data:', error);
                // Alert ini bisa dipertahankan jika ada masalah serius dengan API
                // alert('Gagal memuat data dari database. Silakan coba lagi.');
            }
        }

        // Muat data GeoJSON statis hanya SEKALI saat inisialisasi
        async function loadStaticGeoJson() {
            try {
                // Clear existing static GeoJSON if any (e.g., on first load)
                staticGeoJsonLayerGroup.clearLayers();

                const geojsonResponse = await fetch('{{ asset('storage/kecamatan.geojson') }}');
                const geojsonKecamatan = await geojsonResponse.json();
                kecamatanGeoJsonData = geojsonKecamatan; // Simpan data ke variabel global

                L.geoJson(geojsonKecamatan, {
                    style: function (feature) {
                        return {
                            fillColor: '#FFD700', // Gold color
                            weight: 2,
                            opacity: 1,
                            color: '#000000',      // Black border
                            dashArray: '3',
                            fillOpacity: 0      // Reduced fill opacity to see through
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        // Tidak ada layer.bindPopup() di sini agar tidak bisa diklik
                        // Namun, jika Anda ingin menampilkan tooltip (bukan popup) saat hover, bisa ditambahkan di sini:
                        // if (feature.properties && feature.properties.nama) {
                        //     layer.bindTooltip('Kecamatan: ' + feature.properties.nama);
                        // }
                    }
                }).addTo(staticGeoJsonLayerGroup); // Tambahkan ke layer group statis
            } catch (error) {
                console.error('Error loading static GeoJSON:', error);
                // alert('Gagal memuat data batas kecamatan. Silakan coba lagi.'); // Opsional alert
            }
        }


        // Muat fitur saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Pertama muat GeoJSON statis dan simpan datanya
            loadStaticGeoJson().then(() => {
                // Setelah GeoJSON statis dimuat, baru muat fitur dinamis
                // Ini memastikan kecamatanGeoJsonData sudah tersedia saat loadFeatures dipanggil
                loadFeatures();
            });
        });

        // Event listener untuk saat modal ditutup
        // Memuat ulang fitur dinamis setelah modal ditutup untuk menampilkan data baru/terupdate
        // dan membersihkan layer gambar sementara (drawnItems)
        ['CreatePointModal', 'CreatePolylineModal', 'CreatePolygonModal'].forEach(function(modalId) {
            $('#' + modalId).on('hidden.bs.modal', function () {
                loadFeatures(); // Hanya muat ulang data dinamis
            });
        });
    </script>
@endsection
