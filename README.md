🥭 MAGIS: Mango Agricultural Geospatial & Intelligence System
Aplikasi WebGIS ini adalah platform terintegrasi yang dirancang untuk membantu para petani, pemilik lahan, dan agronom dalam memvisualisasikan, mengelola, dan menganalisis data spasial pertanian mangga secara efisien, guna mendukung pengambilan keputusan yang lebih cerdas dan strategis.

📝 Deskripsi Proyek
MAGIS (Mango Agricultural Geospatial & Intelligence System) adalah sebuah sistem informasi geografis berbasis web yang bertujuan untuk menjadi pusat data dan analisis untuk lahan pertanian mangga. Proyek ini mengatasi tantangan dalam manajemen data pertanian yang seringkali tersebar dan tidak terstandar.

Dengan MAGIS, pengguna dapat dengan mudah memetakan aset, memantau statistik, dan mengelola informasi detail dari setiap lahan, yang semuanya divisualisasikan dalam antarmuka yang modern dan mudah digunakan.

Platform ini menampilkan:

Dashboard ringkasan dengan data statistik dan grafik.
&lt;br>&lt;img src="https://i.ibb.co/L6WvFpc/dashboard-magis.png" width="400"/>

Peta interaktif dengan kemampuan untuk menambah, melihat, dan mengedit data spasial.
&lt;br>&lt;img src="https://i.ibb.co/Ld1JgVf/map-magis.png" width="400"/>

Tabel data yang terorganisir dengan fitur pencarian dan filter.
&lt;br>&lt;img src="https://i.ibb.co/hZ0b4M8/table-magis.png" width="400"/>

🚀 Fitur Utama
📊 Dashboard Analitik
Statistik Utama: Menampilkan ringkasan data penting seperti total poligon (lahan), total titik, dan jumlah pengguna aktif.
Grafik Interaktif: Visualisasi perbandingan luas lahan berdasarkan jenis tanaman mangga melalui grafik batang yang dinamis.
Informasi Sistem: Menampilkan status sistem, versi, dan waktu pembaruan terakhir.
🗺️ Peta Interaktif (Map)
Visualisasi Data: Menampilkan semua data spasial (Points, Polylines, Polygons) dari database langsung di atas peta.
Manajemen Data Spasial: Pengguna dapat menambah, mengedit, dan menghapus data langsung dari peta menggunakan kontrol Leaflet Draw.
Popup Informasi: Klik pada setiap fitur di peta untuk menampilkan popup yang berisi informasi detail, termasuk gambar, nama pemilik, luas lahan, dan lainnya.
Deteksi Interseksi: Secara otomatis mendeteksi dan menampilkan informasi kecamatan yang berpotongan dengan data poligon lahan.
Geolocation: Menemukan lokasi pengguna saat ini dan menampilkannya di peta untuk mempermudah orientasi lapangan.
📋 Tabel Data Interaktif (Table)
Manajemen Data Terstruktur: Semua data spasial disajikan dalam bentuk tabel yang rapi dan terorganisir.
Pemisahan Data: Tampilan tabel dipisahkan berdasarkan tipe fitur (Points, Polylines, Polygons) untuk manajemen yang lebih fokus.
Fitur Pencarian & Sorting: Dilengkapi dengan fungsionalitas DataTables untuk melakukan pencarian cepat dan mengurutkan data berdasarkan kolom.
🛠️ Teknologi yang Digunakan
Backend
PHP – Bahasa utama pengembangan sisi server.
Laravel – Framework backend modern untuk manajemen rute, ORM (Eloquent), validasi, dan arsitektur MVC.
Database
PostgreSQL – Sistem basis data relasional objek yang andal dan kuat.
PostGIS – Ekstensi PostgreSQL untuk menyimpan, mengkueri, dan memproses data geospasial.
Frontend
HTML5 & CSS3 – Struktur dan desain antarmuka pengguna.
Bootstrap 5 – Framework CSS responsif untuk komponen UI yang konsisten.
Font Awesome – Library ikon untuk memperkaya visual antarmuka.
JavaScript (ES6+) – Untuk interaktivitas dan logika di sisi klien:
jQuery – Mempermudah manipulasi DOM dan event handling.
Leaflet.js – Library utama untuk pembuatan peta interaktif.
Leaflet.Draw – Plugin untuk fitur menggambar dan mengedit pada peta.
Chart.js – Untuk membuat grafik dan bagan yang dinamis di dashboard.
DataTables – Untuk menambahkan fitur interaktif pada tabel HTML.
⚙️ Instalasi dan Penggunaan
Ikuti langkah-langkah berikut untuk menjalankan proyek ini secara lokal:

Persiapan
Pastikan perangkat Anda sudah terpasang:

PHP (disarankan versi 8.1 atau lebih baru)
Composer
PostgreSQL dengan ekstensi PostGIS
Git
Kloning Repositori:

Bash

git clone https://github.com/username/magis-repository.git
cd magis-repository
(Ganti username/magis-repository.git dengan URL repositori Anda)

Instal Dependensi:

Bash

composer install
Konfigurasi Environment:

Salin file .env.example menjadi .env:
Bash

cp .env.example .env
Buka file .env dan sesuaikan konfigurasi database Anda:
Cuplikan kode

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database_magis
DB_USERNAME=username_postgres
DB_PASSWORD=password_anda
Generate Application Key:

Bash

php artisan key:generate
Migrasi Database:
Jalankan migrasi untuk membuat semua tabel yang diperlukan.

Bash

php artisan migrate
Buat Symbolic Link:
Penting untuk membuat storage link agar file yang diunggah dapat diakses publik.

Bash

php artisan storage:link
Jalankan Aplikasi:

Bash

php artisan serve
Akses Aplikasi:
Buka browser Anda dan kunjungi http://127.0.0.1:8000.

Copyright © 2025 by Muhammad Naufal Hidayat.

