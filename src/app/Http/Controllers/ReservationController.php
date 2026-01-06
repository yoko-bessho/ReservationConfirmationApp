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


    public function import(importReservationRequest $request)
    {
        $file = $request->file('file');

        try {
            Excel::import(new ReservationImport,$file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $importErrors = [];
            $failures = $e->failures();
            foreach ($failures as $failure) {
                $importErrors[] = [
                    'row'       => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors'    => $failure->errors(),
                    'values'    => $failure->values(),
                ];
            }
            return back()->with('importErrors', $importErrors);
        }
        
        return back()->with('success', 'インポート完了');
    }
}