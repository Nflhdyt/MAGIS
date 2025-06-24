<?php

namespace App\Http\Controllers;

use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PolygonController extends Controller
{
    /**
     * Menyimpan polygon baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric',
            'nama_kebun' => 'required|string|max:255',
            'nama_tanaman' => 'required|string|max:255',
            'jumlah_panen' => 'required|integer',
            'area_km' => 'nullable|numeric',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar untuk polygon: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar.');
            }
        }

        try {
            Polygon::create([
                'nama_pemilik' => $request->nama_pemilik,
                'luas_lahan' => $request->luas_lahan,
                'nama_kebun' => $request->nama_kebun,
                'nama_tanaman' => $request->nama_tanaman,
                'jumlah_panen' => $request->jumlah_panen,
                'area_km' => $request->area_km,
                'image' => $imagePath,
                'user_created' => auth()->check() ? auth()->user()->name : 'Guest',
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan polygon: " . $e->getMessage());
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan polygon. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polygon berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit polygon.
     */
    public function edit(Polygon $polygon)
    {
        return view('polygons.edit', compact('polygon'));
    }

    /**
     * Memperbarui polygon di database.
     */
    public function update(Request $request, Polygon $polygon)
    {
        $request->validate([
            'nama_pemilik' => 'required|string|max:255',
            'luas_lahan' => 'required|numeric',
            'nama_kebun' => 'required|string|max:255',
            'nama_tanaman' => 'required|string|max:255',
            'jumlah_panen' => 'required|integer',
            'area_km' => 'nullable|numeric',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $polygon->image;
        if ($request->hasFile('image')) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar saat update polygon: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar baru.');
            }
        } elseif ($request->input('delete_image_checkbox')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }
        }

        try {
            $polygon->update([
                'nama_pemilik' => $request->nama_pemilik,
                'luas_lahan' => $request->luas_lahan,
                'nama_kebun' => $request->nama_kebun,
                'nama_tanaman' => $request->nama_tanaman,
                'jumlah_panen' => $request->jumlah_panen,
                'area_km' => $request->area_km,
                'image' => $imagePath,
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui polygon: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui polygon. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polygon berhasil diperbarui!');
    }

    /**
     * Menghapus polygon dari database.
     */
    public function destroy(Polygon $polygon)
    {
        try {
            if ($polygon->image && Storage::disk('public')->exists($polygon->image)) {
                Storage::disk('public')->delete($polygon->image);
            }
            $polygon->delete();
        } catch (\Exception $e) {
            Log::error("Gagal menghapus polygon: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus polygon. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polygon berhasil dihapus!');
    }
}
