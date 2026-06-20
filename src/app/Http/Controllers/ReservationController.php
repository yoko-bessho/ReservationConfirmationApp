<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportReservationRequest;
use App\Imports\ReservationImport;
use App\Exports\ReservationExport;
use App\Models\Reservation;
use App\Services\ReservationDiffService;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

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
            return response()->json([
                'latestImportAt'     => null,
                'previousImportAt'   => null,
                'latestReservations' => [],
                'importDates'        => [],
                'addedDiffs'         => new \stdClass(),
                'deletedDiffs'       => new \stdClass(),
            ]);
        }

        $latestReservations = Reservation::where('import_at', $latestImportAt)->get();
        $previousReservations = Reservation::where('import_at', $previousImportAt)->get();

        $result = $diffService->calculate(
            $latestReservations,
            $previousReservations,
        );

        $importDates = DB::table('reservations')
            ->where('import_at', '<', $latestImportAt->format('Y-m-d H:i:s'))
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

        return response()->json([
            'latestImportAt'     => $latestImportAt,
            'previousImportAt'   => $previousImportAt,
            'latestReservations' => $latestReservations->values(),
            'importDates'        => $importDates->values(),
            'addedDiffs'         => (object) $result['addedDiffs']->toArray(),
            'deletedDiffs'       => (object) $result['deletedDiffs']->toArray(),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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
        );

        $importDates = DB::table('reservations')
            ->where('import_at', '<', $latestImportAt->format('Y-m-d H:i:s'))
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

        return response()->json([
            'latestImportAt'     => $latestImportAt,
            'previousImportAt'   => $previousImportAt,
            'latestReservations' => $latestReservations->values(),
            'importDates'        => $importDates->values(),
            'addedDiffs'         => (object) $result['addedDiffs']->toArray(),
            'deletedDiffs'       => (object) $result['deletedDiffs']->toArray(),
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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