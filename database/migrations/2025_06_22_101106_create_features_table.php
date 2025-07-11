<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('featureable_type'); // Contoh: App\Models\Point, App\Models\Polyline
            $table->unsignedBigInteger('featureable_id'); // ID dari Point, Polyline, atau Polygon
            $table->string('user_created')->nullable();
            $table->timestamps();

            // Menambahkan indeks untuk kolom polimorfik
            $table->index(['featureable_type', 'featureable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
