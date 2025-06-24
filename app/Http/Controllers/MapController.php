<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Menampilkan halaman peta interaktif.
     */
    public function index()
    {
        return view('map');
    }
}
