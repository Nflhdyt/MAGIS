@extends('layout.template')

@section('styles')
    <style>
        /* ========================================================================= */
        /* TEMA UTAMA & LANDING PAGE (MAGIS) */
        /* ========================================================================= */

        :root {
            --primary-color: #86C232; /* Hijau terang untuk Aksen & CTA */
            --dark-background: #1a5953; /* Hijau tua untuk background landing page */
            --light-background: #f4f7f6; /* Latar belakang terang untuk dashboard */
            --text-on-dark: #ffffff; /* Teks putih di atas background gelap */
            --text-on-light: #2d3748; /* Teks gelap utama di atas background terang */
            --text-secondary-on-light: #5a677d; /* Teks sekunder abu-abu */
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        /* Background Utama Landing Page */
        .magis-landing-bg {
            background-color: var(--dark-background);
            position: relative;
            padding-top: 80px; /* Ruang untuk header */
            padding-bottom: 4rem;
        }

        /* Hero Section Layout (Landing Page) */
        .magis-hero-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem 0;
            min-height: 90vh;
        }

        @media (max-width: 992px) {
            .magis-hero-section {
                flex-direction: column;
                text-align: center;
                min-height: auto;
            }
        }

        .magis-hero-content {
            flex-basis: 50%;
            animation: fadeInUp 0.8s ease-out;
        }

        .magis-hero-image-container {
            flex-basis: 50%;
            animation: fadeIn 1.2s ease-out;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .magis-hero-image {
            max-width: 100%;
            height: auto;
        }

        /* Styling Teks (Landing Page) */
        .trusted-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            border: 1px solid var(--primary-color);
        }

        .magis-hero-title {
            font-size: clamp(2.2rem, 4vw, 3.5rem);
            font-weight: 800;
            color: var(--text-on-dark);
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .magis-hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.1rem);
            color: var(--text-on-dark);
            opacity: 0.9;
            max-width: 550px;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        @media (max-width: 992px) {
            .magis-hero-subtitle {
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* Buttons (Landing Page) */
        .magis-btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-cta {
            background-color: var(--primary-color);
            color: #111;
            box-shadow: 0 4px 20px rgba(134, 194, 50, 0.4);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(134, 194, 50, 0.6);
            color: #000;
        }

        /* ========================================================================= */
        /* STYLES UNTUK BAGIAN DASHBOARD (MENGIKUTI TEMA UTAMA) */
        /* ========================================================================= */

        .dashboard-section-bg {
            background-color: var(--light-background);
            position: relative;
            padding: 4rem 0;
            border-top: 1px solid var(--card-border);
        }

        .db-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--card-border);
        }

        .db-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .db-hero-section {
            text-align: center;
            padding-bottom: 3rem; /* Menghilangkan padding atas agar menyatu */
        }

        .db-hero-title {
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            font-weight: 800;
            color: var(--text-on-light);
            margin-bottom: 1.5rem;
        }

        .db-hero-subtitle {
            font-size: clamp(1.1rem, 2.5vw, 1.25rem);
            color: var(--text-secondary-on-light);
            max-width: 600px;
            margin: 0 auto 2.5rem;
            line-height: 1.6;
        }

        .db-stats-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }

        .db-stats-card {
            padding: 2rem;
            text-align: center;
        }

        .db-stats-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color); /* Warna hijau dari tema */
            display: block;
            margin-bottom: 0.5rem;
        }

        .db-stats-label {
            color: var(--text-secondary-on-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
        }

        .db-chart-container {
             padding: 2rem;
             margin-top: 3rem;
        }

         .db-chart-container::before {
             content: '';
             position: absolute;
             top: 0; left: 0; right: 0;
             height: 5px;
             background: var(--primary-color); /* Garis atas hijau */
         }

        .db-chart-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-on-light);
            margin-bottom: 1.5rem;
            text-align: center;
        }

        /* Animasi */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
@endsection

@section('content')
    {{-- BAGIAN 1: LANDING PAGE MAGIS --}}
    <div class="magis-landing-bg">
        <div class="container mx-auto px-4 relative z-10">
            <div class="magis-hero-section">
                {{-- Konten Kiri: Teks & CTA --}}
                <div class="magis-hero-content">
                    <div class="trusted-badge">
                        <i class="fas fa-shield-alt"></i>
                        <span>MAGIS</span>
                    </div>
                    <h1 class="magis-hero-title">
                        Mango Agricultural Geospatial Intelligence System
                    </h1>
                    <p class="magis-hero-subtitle">
                        We proudly serve the mango farming community with MAGIS, a powerful WebGIS platform built to empower farmers, and agronomists, through smart mapping, insightful analysis, and real-time collaboration.
                    </p>
                    <div class="cta-buttons">
                        <a href="{{ url('/map') }}" class="magis-btn btn-cta">
                            Go to map
                        </a>
                    </div>
                </div>

                {{-- Konten Kanan: Gambar --}}
                <div class="magis-hero-image-container">
                    {{-- PASTIKAN path ini benar dan Anda sudah menjalankan `php artisan storage:link` --}}
                    <img src="{{ asset('storage/images/landing_bg.png') }}" alt="MAGIS Platform" class="magis-hero-image">
                </div>
            </div>
        </div>
    </div>

    {{-- BAGIAN 2: FUNGSI DASHBOARD --}}
    <div class="dashboard-section-bg">
        <div class="container mx-auto px-4 py-8 relative z-10">

            <div class="db-hero-section">
                <h1 class="db-hero-title">
                    <i class="fas fa-chart-pie me-3" style="color: var(--primary-color);"></i>
                    Dashboard Overview
                </h1>
                <p class="db-hero-subtitle">
                    Explore, analyze, and manage your geographic data. An integrated platform for powerful and intuitive spatial data visualization.
                </p>
            </div>

            {{-- Stats Section --}}
            <div class="db-stats-grid">
                <div class="db-card db-stats-card">
                    <span class="db-stats-number">{{ number_format($totalPolygons ?? 0) }}</span>
                    <span class="db-stats-label">Total Polygon</span>
                    <div class="mt-3">
                        <i class="fas fa-draw-polygon text-3xl" style="color: #cbd5e1;"></i>
                    </div>
                </div>
                <div class="db-card db-stats-card">
                    <span class="db-stats-number">{{ number_format($totalPoints ?? 125) }}</span>
                    <span class="db-stats-label">Total Points</span>
                    <div class="mt-3">
                        <i class="fas fa-map-pin text-3xl" style="color: #cbd5e1;"></i>
                    </div>
                </div>
                <div class="db-card db-stats-card">
                    <span class="db-stats-number">{{ number_format($totalUsers ?? 42) }}</span>
                    <span class="db-stats-label">Active Users</span>
                    <div class="mt-3">
                        <i class="fas fa-users text-3xl" style="color: #cbd5e1;"></i>
                    </div>
                </div>
            </div>

            {{-- Chart Section --}}
            <div class="db-card db-chart-container">
                <h3 class="db-chart-title">
                    <i class="fas fa-chart-bar me-2"></i>
                    Plant Area Comparison (KM²)
                </h3>

                @if(!empty($labels) && !empty($data))
                    <div style="position: relative; height: 400px;">
                        <canvas id="chartTanaman"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-16">
                        <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 text-lg font-medium">No plant data available</p>
                        <p class="text-gray-400 text-sm mt-2">Data will appear once you add plant information</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Chart.js dan plugins --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

    <script>
        // Register Chart.js plugin
        Chart.register(ChartDataLabels);

        document.addEventListener('DOMContentLoaded', function() {
            console.log('Landing page and dashboard loaded.');

            // Data untuk chart (diterima dari controller)
            const labels = @json($labels ?? []);
            const data = @json($data ?? []);

            if (labels.length > 0 && data.length > 0) {
                const ctx = document.getElementById('chartTanaman').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Total Area (Km²)',
                            data: data,
                            backgroundColor: 'rgba(134, 194, 50, 0.8)', // Warna Hijau Tema
                            borderColor: 'rgb(134, 194, 50)', // Warna Hijau Tema
                            borderWidth: 2,
                            borderRadius: 8,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Total Area: ${new Intl.NumberFormat('id-ID').format(context.parsed.y)} KM²`;
                                    }
                                }
                            },
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: (value) => new Intl.NumberFormat('id-ID').format(value),
                                color: '#374151',
                                font: { weight: 'bold' }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Area (KM²)', font: {size: 14} },
                                grid: {
                                    drawOnChartArea: true,
                                    borderColor: 'transparent'
                                }
                            },
                            x: {
                                title: { display: true, text: 'Plant Type', font: {size: 14} },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
