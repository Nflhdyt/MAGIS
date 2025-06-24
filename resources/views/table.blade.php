{{-- resources/views/table.blade.php --}}

@extends('layout.template')

@section('styles')
    {{-- DataTables CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
    <style>
        .card-header {
            background-color: #1a5953; /* Warna hijau tua dari tema MAGIS */
            color: white;
        }
        .card-header .h4 {
            margin-bottom: 0;
            font-size: 1.2rem;
        }
        .table th {
            font-weight: 600;
        }
        .img-thumbnail-table {
            max-width: 80px;
            height: auto;
            display: block;
            margin: auto;
            border-radius: 3px;
        }
        .btn-group .btn, .btn-group form {
            margin-right: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <h1 class="text-center mb-5 display-5 fw-bold">Tabel Data Kebun</h1>

        {{-- Alert Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- CARD 1: UNTUK DATA POINTS --}}
        <div class="card shadow-sm mb-5">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa-solid fa-map-pin me-2"></i> Data Points</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pointsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemilik</th>
                                <th>Nama Kebun</th>
                                <th>Tanaman</th>
                                <th>Foto</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan dimuat oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- CARD 2: UNTUK DATA POLYGONS --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0"><i class="fa-solid fa-draw-polygon me-2"></i> Data Polygons</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="polygonsTable" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pemilik</th>
                                <th>Luas Lahan</th>
                                <th>Nama Kebun</th>
                                <th>Tanaman</th>
                                <th>Foto</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan dimuat oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    {{-- jQuery & DataTables JS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // URL API Anda yang tidak diubah
            const apiUrl = '{{ route('api.all_features') }}';

            // Inisialisasi Tabel Points
            $('#pointsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: apiUrl,
                    // Saring data di sisi klien
                    dataSrc: function(json) {
                        // Hanya kembalikan fitur yang tipenya 'Point'
                        return json.features.filter(feature => feature.properties.feature_type === 'Point');
                    }
                },
                // Kolom disederhanakan karena kita tahu semua data di sini adalah Point
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'properties.nama_pemilik', defaultContent: '-' },
                    { data: 'properties.nama_kebun', defaultContent: '-' },
                    { data: 'properties.nama_tanaman', defaultContent: '-' },
                    { data: 'properties.image', render: (data) => data ? `<img src="{{ asset('storage') }}/${data}" class="img-thumbnail-table" alt="Foto">` : '-' },
                    { data: 'properties.user_created', defaultContent: '-' },
                    { data: 'properties.id', render: function(data, type, row) {
                        // URL Aksi spesifik untuk Point
                        var editUrl = `/points/${data}/edit`;
                        var deleteUrl = `/points/${data}`;
                        return generateActionButtons(editUrl, deleteUrl);
                    }}
                ]
            });

            // Inisialisasi Tabel Polygons
            $('#polygonsTable').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: apiUrl,
                    // Saring data di sisi klien
                    dataSrc: function(json) {
                        // Hanya kembalikan fitur yang tipenya 'Polygon'
                        return json.features.filter(feature => feature.properties.feature_type === 'Polygon');
                    }
                },
                 // Kolom disederhanakan karena kita tahu semua data di sini adalah Polygon
                columns: [
                    { data: null, render: (data, type, row, meta) => meta.row + 1 },
                    { data: 'properties.nama_pemilik', defaultContent: '-' },
                    { data: 'properties.luas_lahan', render: (data) => data ? `${data} Ha` : '-' },
                    { data: 'properties.nama_kebun', defaultContent: '-' },
                    { data: 'properties.nama_tanaman', defaultContent: '-' },
                    { data: 'properties.image', render: (data) => data ? `<img src="{{ asset('storage') }}/${data}" class="img-thumbnail-table" alt="Foto">` : '-' },
                    { data: 'properties.user_created', defaultContent: '-' },
                    { data: 'properties.id', render: function(data, type, row) {
                        // URL Aksi spesifik untuk Polygon
                        var editUrl = `/polygons/${data}/edit`;
                        var deleteUrl = `/polygons/${data}`;
                        return generateActionButtons(editUrl, deleteUrl);
                    }}
                ]
            });

            // Fungsi bantuan untuk membuat tombol aksi
            function generateActionButtons(editUrl, deleteUrl) {
                var csrfToken = '{{ csrf_token() }}';
                return `
                    <div class="btn-group" role="group">
                        <a href="${editUrl}" class="btn btn-warning btn-sm text-white" title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                        <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Apakah Anda yakin?');" style="display:inline-block;">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                <i class="fa-solid fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                `;
            }
        });
    </script>
@endsection
