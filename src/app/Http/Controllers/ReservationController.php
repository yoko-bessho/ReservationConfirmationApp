<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function excelImport()
    {
        return view('excel_import');
    }

    public function index()
    {
        return view('index');
    }
}
