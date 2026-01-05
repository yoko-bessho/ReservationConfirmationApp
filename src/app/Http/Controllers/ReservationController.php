<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportReservationRequest;
use App\Services\ImportService;
use App\Imports\ReservationImport;
use Maatwebsite\Excel\Facades\Excel;
class ReservationController extends Controller
{
    public function showImportForm()
    {
        return view('excel_import');
    }

    public function import(Request $request)
    {
        $file = $request->file('file');

        Excel::import(
            new ReservationImport(app(ImportService::class)),
            $file
        );

        return back()->with('success', 'インポート完了');

    }

}
