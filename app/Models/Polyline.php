<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Polyline extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'length_km',
        'image',
        'user_created',
        'geom',
    ];

    // Tambahkan ini
    protected $casts = [
        'length_km' => 'float', // Penting: Pastikan ini cast ke float
    ];

    public function setGeomAttribute($value)
    {
        $this->attributes['geom'] = DB::raw("ST_GeomFromText('{$value}', 4326)");
    }

    public function getGeomAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        $wkt = DB::selectOne("SELECT ST_AsText(geom) AS wkt FROM polylines WHERE id = ?", [$this->id]);
        return $wkt ? $wkt->wkt : null;
    }

    public function getGeojsonAttribute()
    {
        $geojson = DB::selectOne("SELECT ST_AsGeoJSON(geom) AS geojson FROM polylines WHERE id = ?", [$this->id]);
        return $geojson ? json_decode($geojson->geojson, true) : null;
    }
}
