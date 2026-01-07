<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class DiffHistoryController extends Controller
{
    public function index()
    {
        $latestImportAt = Reservation::max('import_at');

        $reservations = Reservation::where('import_at', $latestImportAt)
            ->orderBy('visit_date', 'asc')
            ->get();

        return view('index', compact('reservations', 'latestImportAt'));
    }
}
