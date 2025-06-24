<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\Polyline;
use App\Models\Polygon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Penting: Import DB Facade

class ApiController extends Controller
{
    /**
     * Mengembalikan semua data Point sebagai GeoJSON.
     */
    public function points()
    {
        // Pilih semua kolom, dan secara eksplisit konversi kolom 'geom' ke GeoJSON menggunakan ST_AsGeoJSON
        $points = Point::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();

        $features = [];
        foreach ($points as $point) {
            $properties = $point->toArray();
            // Hapus 'geom' asli (binary) dan 'geojson_geom' dari properti
            unset($properties['geom']);
            unset($properties['geojson_geom']);

            $features[] = [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => json_decode($point->geojson_geom), // Gunakan alias geojson_geom yang sudah di-decode
            ];
        }
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }

    /**
     * Mengembalikan semua data Polyline sebagai GeoJSON.
     */
    public function polylines()
    {
        $polylines = Polyline::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();
        $features = [];
        foreach ($polylines as $polyline) {
            $properties = $polyline->toArray();
            unset($properties['geom']);
            unset($properties['geojson_geom']);

            $features[] = [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => json_decode($polyline->geojson_geom),
            ];
        }
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }

    /**
     * Mengembalikan semua data Polygon sebagai GeoJSON.
     */
    public function polygons()
    {
        $polygons = Polygon::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();
        $features = [];
        foreach ($polygons as $polygon) {
            $properties = $polygon->toArray();
            unset($properties['geom']);
            unset($properties['geojson_geom']);

            $features[] = [
                'type' => 'Feature',
                'properties' => $properties,
                'geometry' => json_decode($polygon->geojson_geom),
            ];
        }
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }

    /**
     * Mengembalikan semua jenis fitur (Point, Polyline, Polygon) sebagai satu GeoJSON FeatureCollection.
     * Ini akan digunakan oleh halaman tabel.
     */
    public function allFeatures()
    {
        $features = [];

        // Ambil semua points
        $points = Point::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();
        foreach ($points as $point) {
            $properties = $point->toArray();
            unset($properties['geom']);
            unset($properties['geojson_geom']);
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($point->geojson_geom),
                'properties' => array_merge($properties, ['feature_type' => 'Point']), // Tambahkan tipe fitur
            ];
        }

        // Ambil semua polylines
        $polylines = Polyline::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();
        foreach ($polylines as $polyline) {
            $properties = $polyline->toArray();
            unset($properties['geom']);
            unset($properties['geojson_geom']);
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($polyline->geojson_geom),
                'properties' => array_merge($properties, ['feature_type' => 'Polyline']), // Tambahkan tipe fitur
            ];
        }

        // Ambil semua polygons
        $polygons = Polygon::select('*', DB::raw('ST_AsGeoJSON(geom) as geojson_geom'))->get();
        foreach ($polygons as $polygon) {
            $properties = $polygon->toArray();
            unset($properties['geom']);
            unset($properties['geojson_geom']);
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geojson_geom),
                'properties' => array_merge($properties, ['feature_type' => 'Polygon']), // Tambahkan tipe fitur
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }
}
