<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Menampilkan halaman tabel data.
     */
    public function index()
    {
        return view('table');
    }
}
