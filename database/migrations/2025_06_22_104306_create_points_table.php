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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik');
            $table->double('luas_lahan');
            $table->string('nama_kebun');
            $table->string('nama_tanaman');
            $table->integer('jumlah_panen');
            $table->string('image')->nullable();
            $table->string('user_created')->nullable(); // Siapa yang membuat data
            $table->timestamps();
        });

        // Menambahkan kolom 'geom' dengan tipe GEOMETRY(Point, 4326) setelah tabel dibuat
        // Ini adalah cara yang benar jika tidak menggunakan library spatial
        DB::statement('ALTER TABLE points ADD COLUMN geom GEOMETRY(Point, 4326)');

        // Opsional: Menambahkan indeks spasial (direkomendasikan untuk performa query spasial)
        DB::statement('CREATE INDEX points_geom_idx ON points USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
