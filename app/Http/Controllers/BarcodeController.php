<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display the barcode generator index page.
     */
    public function index()
    {
        return view('barcodes.index');
    }
}
