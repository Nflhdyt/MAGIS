<?php

namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PointController extends Controller
{
    /**
     * Menampilkan daftar semua point.
     * Biasanya digunakan untuk halaman administrasi atau daftar.
     */
    public function index()
    {
        $points = Point::all();
        return view('points.index', compact('points'));
    }

    /**
     * Menampilkan form untuk membuat point baru.
     */
    public function create()
    {
        return view('points.create');
    }

    /**
     * Menyimpan point baru ke database.
     * Ini dipanggil saat form modal di halaman peta disubmit.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric',
            'nama_kebun' => 'required|string|max:255',
            'nama_tanaman' => 'required|string|max:255',
            'jumlah_panen' => 'required|integer',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar untuk point: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar.');
            }
        }

        try {
            Point::create([
                'nama_pemilik' => $request->nama_pemilik,
                'luas_lahan' => $request->luas_lahan,
                'nama_kebun' => $request->nama_kebun,
                'nama_tanaman' => $request->nama_tanaman,
                'jumlah_panen' => $request->jumlah_panen,
                'image' => $imagePath,
                'user_created' => auth()->check() ? auth()->user()->name : 'Guest',
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan point: " . $e->getMessage());
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan point. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Point berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail point tertentu.
     */
    public function show(Point $point)
    {
        return view('points.show', compact('point'));
    }

    /**
     * Menampilkan form untuk mengedit point yang sudah ada.
     */
    public function edit(Point $point)
    {
        return view('points.edit', compact('point'));
    }

    /**
     * Memperbarui point yang sudah ada di database.
     */
    public function update(Request $request, Point $point)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric',
            'nama_kebun' => 'required|string|max:255',
            'nama_tanaman' => 'required|string|max:255',
            'jumlah_panen' => 'required|integer',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $point->image;
        if ($request->hasFile('image')) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar saat update point: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar baru.');
            }
        } elseif ($request->input('delete_image_checkbox')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }
        }

        try {
            $point->update([
                'nama_pemilik' => $request->nama_pemilik,
                'luas_lahan' => $request->luas_lahan,
                'nama_kebun' => $request->nama_kebun,
                'nama_tanaman' => $request->nama_tanaman,
                'jumlah_panen' => $request->jumlah_panen,
                'image' => $imagePath,
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui point: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui point. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Point berhasil diperbarui!');
    }

    /**
     * Menghapus point dari database.
     */
    public function destroy(Point $point)
    {
        try {
            if ($point->image && Storage::disk('public')->exists($point->image)) {
                Storage::disk('public')->delete($point->image);
            }
            $point->delete();
        } catch (\Exception $e) {
            Log::error("Gagal menghapus point: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus point. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Point berhasil dihapus!');
    }
}
