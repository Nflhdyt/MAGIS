<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Polygon; // Pastikan Anda mengimpor model Polygon
// Jika data tanaman juga ada di Point atau Polyline, import juga modelnya:
// use App\Models\Point;
// use App\Models\Polyline;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     */
    public function index()
    {
        // Mengambil data tanaman dari Polygon.
        // Asumsi kolom untuk nama tanaman adalah 'nama_tanaman' atau semacamnya
        // Dan kolom untuk jumlah panen adalah 'jumlah_panen'
        // Jika nama kolom berbeda, sesuaikan di bawah.

        // Jika Anda memiliki kolom 'nama_tanaman' di tabel 'polygons':
        $tanamanData = Polygon::select('nama_tanaman', \DB::raw('SUM(jumlah_panen) as total_panen'))
                              ->groupBy('nama_tanaman')
                              ->get();

        // Mengubah format data agar mudah digunakan oleh Chart.js
        $labels = $tanamanData->pluck('nama_tanaman')->toArray();
        $data = $tanamanData->pluck('total_panen')->toArray();

        // Anda juga bisa mengambil data ringkasan lainnya jika diperlukan, contoh:
        $totalPolygons = Polygon::count();
        // $totalPoints = \App\Models\Point::count();
        // $totalPolylines = \App\Models\Polyline::count();


        return view('dashboard', compact('labels', 'data', 'totalPolygons'));
        // Jika Anda punya data lain, tambahkan ke compact(), contoh:
        // return view('dashboard', compact('labels', 'data', 'totalPolygons', 'totalPoints', 'totalPolylines'));
    }
}
