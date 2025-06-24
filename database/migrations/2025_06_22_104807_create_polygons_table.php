<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- Penting: Pastikan ini ada!

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('polygons', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->double('luas_lahan');
            $table->string('nama_kebun');
            $table->string('nama_tanaman');
            $table->integer('jumlah_panen');
            $table->double('area_km')->nullable(); // Luas area dalam KM persegi
            $table->string('image')->nullable();
            $table->string('user_created')->nullable(); // Siapa yang membuat data
            $table->timestamps();
        });

        // Menambahkan kolom 'geom' dengan tipe GEOMETRY(Polygon, 4326)
        DB::statement('ALTER TABLE polygons ADD COLUMN geom GEOMETRY(Polygon, 4326)');

        // Opsional: Menambahkan indeks spasial
        DB::statement('CREATE INDEX polygons_geom_idx ON polygons USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polygons');
    }
};
