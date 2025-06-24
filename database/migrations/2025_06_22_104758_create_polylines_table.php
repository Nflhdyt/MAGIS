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
        Schema::create('polylines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->double('length_km')->nullable(); // Panjang dalam KM
            $table->string('image')->nullable();
            $table->string('user_created')->nullable(); // Siapa yang membuat data
            $table->timestamps();
        });

        // Menambahkan kolom 'geom' dengan tipe GEOMETRY(LineString, 4326)
        DB::statement('ALTER TABLE polylines ADD COLUMN geom GEOMETRY(LineString, 4326)');

        // Opsional: Menambahkan indeks spasial
        DB::statement('CREATE INDEX polylines_geom_idx ON polylines USING GIST (geom)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polylines');
    }
};
