<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportReservationRequest;
use App\Imports\ReservationImport;
use App\Exports\ReservationExport;
use App\Models\Reservation;
use App\Services\ReservationDiffService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\Console\Input\Input;

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


    public function index(ReservationDiffService $diffService)
    {
        $latestImportAt = Reservation::getLatestImportAt();
        $previousImportAt = Reservation::getPreviousImportAt();

        if (!$latestImportAt || !$previousImportAt) {
            return view('index', [
                'latestImportAt' => $latestImportAt,
                'previousImportAt' => $previousImportAt,
                'latestReservations' => collect(),
                'previousReservations' => collect(),
                'addedDiffs' => collect(),
                'deletedDiffs' => collect(),
            ]);
        }

        $latestReservations = Reservation::where('import_at', $latestImportAt)->get();
        $previousReservations = Reservation::where('import_at', $previousImportAt)->get();

        $result = $diffService->calculate(
            $latestReservations,
            $previousReservations,
        );

        // インポート日時選択肢用
        $importDates = Reservation::select('import_at')
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

        return view('index', array_merge($result, [
            'latestImportAt' => $latestImportAt,
            'previousImportAt' => $previousImportAt,
            'latestReservations' => $latestReservations,
            'importDates' => $importDates,
        ]));
    }


    public function check(Request $request, ReservationDiffService $diffService)
    {
        $latestImportAt = Reservation::getLatestImportAt();
        $previousImportAt = $request->input('from_import_at');

        $latestReservations = Reservation::where('import_at', $latestImportAt)->get();
        $previousReservations = Reservation::where('import_at', $previousImportAt)->get();

        $result = $diffService->calculate(
            $latestReservations,
            $previousReservations,
            $latestImportAt,
            $previousImportAt
        );

        $importDates = Reservation::where('import_at', '<', $latestImportAt)
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

            return view('index', array_merge($result, [
            'importDates'      => $importDates,
            'latestImportAt' => $latestImportAt,
            'latestReservations' => $latestReservations,
            'previousImportAt' => $previousImportAt,
        ]));
    }

    public function export(Request $request, ReservationDiffService $diffService)
    {
        $latestImportAt = Reservation::getLatestImportAt();
        
        $previousImportAt = Reservation::getPreviousImportAt();

        if ($request->input('from_import_at')) {
            $previousImportAt = $request->input('from_import_at');
        }

        $latestReservations = Reservation::where('import_at', $latestImportAt)->get();
        $previousReservations = Reservation::where('import_at', $previousImportAt)->get();
        $result = $diffService->calculate(
            $latestReservations,
            $previousReservations,
        );

        return Excel::download(
            new ReservationExport(
                'exports_reservations_diff', array_merge($result, [
                    'latestReservations' => $latestReservations,
                ])
            ),
            'reservations_diff.xlsx'
        );
    }
}