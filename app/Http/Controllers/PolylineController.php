<?php

namespace App\Http\Controllers;

use App\Models\Polyline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PolylineController extends Controller
{
    /**
     * Menyimpan polyline baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'length_km' => 'nullable|numeric',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar untuk polyline: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar.');
            }
        }

        try {
            Polyline::create([
                'name' => $request->name,
                'description' => $request->description,
                'length_km' => $request->length_km,
                'image' => $imagePath,
                'user_created' => auth()->check() ? auth()->user()->name : 'Guest',
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan polyline: " . $e->getMessage());
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan polyline. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polyline berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit polyline.
     */
    public function edit(Polyline $polyline)
    {
        return view('polylines.edit', compact('polyline'));
    }

    /**
     * Memperbarui polyline di database.
     */
    public function update(Request $request, Polyline $polyline)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'length_km' => 'nullable|numeric',
            'geom' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $polyline->image;
        if ($request->hasFile('image')) {
            try {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('images', 'public');
            } catch (\Exception $e) {
                Log::error("Gagal mengupload gambar saat update polyline: " . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal mengupload gambar baru.');
            }
        } elseif ($request->input('delete_image_checkbox')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
            }
        }

        try {
            $polyline->update([
                'name' => $request->name,
                'description' => $request->description,
                'length_km' => $request->length_km,
                'image' => $imagePath,
                'geom' => $request->geom,
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui polyline: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui polyline. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polyline berhasil diperbarui!');
    }

    /**
     * Menghapus polyline dari database.
     */
    public function destroy(Polyline $polyline)
    {
        try {
            if ($polyline->image && Storage::disk('public')->exists($polyline->image)) {
                Storage::disk('public')->delete($polyline->image);
            }
            $polyline->delete();
        } catch (\Exception $e) {
            Log::error("Gagal menghapus polyline: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus polyline. Silakan coba lagi.');
        }

        // PERBAIKAN DI SINI
        return redirect()->route('map.index')->with('success', 'Polyline berhasil dihapus!');
    }
}
