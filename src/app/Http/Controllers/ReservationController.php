<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImportReservationRequest;
use App\Services\ImportService;
use App\Imports\ReservationImport;
use App\Models\Reservation;
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


    public function index()
    {
        // 最新とその一つ前のimport_at
        $latestImportAt = Reservation::max('import_at');

        $previousImportAt = Reservation::where('import_at', '<', $latestImportAt)->max('import_at');

        // 任意のimport_at選択肢
        $importDates = Reservation::select('import_at')
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

        // 最新とその一つ前のimportデータ
        $latest = Reservation::getLatestImportData();
        $previous = Reservation::getPreviousImportData();

        // 初回importなどで比較不可
        if (!$latest || !$previous) {
            return view('index', [
                'latestImportAt' => $latest ? $latest['import_at'] : null,
                'latestReservations' => collect(),
                'added' => collect(),
                'deleted' => collect(),
            ]);
        }
        $latestReservations = $latest['reservations'];
        $previousReservations = $previous['reservations'];

        // 配列ではなくキー比較にする
        $latestKeyed = $latestReservations->keyBy(function ($r) {
            return $r->visit_date . '_' . $r->patient_id . '_' . $r->reservation_content;
        });
        $previousKeyed = $previousReservations->keyBy(function ($r) {
            return $r->visit_date . '_' . $r->patient_id . '_' . $r->reservation_content;
        });

        $addedDiffs = $latestKeyed->diffKeys($previousKeyed);
        // previousにしかないキーを算出
        $deletedDiffs = $previousKeyed->diffKeys($latestKeyed);

        return view('index', compact(
            'latestReservations',
            'latestImportAt',
            'latestImportAt',
            'previousImportAt',
            'addedDiffs',
            'deletedDiffs',
            'importDates',
        ));
    }


        public function check(Request $request)
    {
        $latestImportAt = Reservation::max('import_at');
        $previousImportAt = $request->input('from_import_at');

        // 最新予約データ
        $latestReservations = Reservation::where('import_at', $latestImportAt)->get();
        // 指定された過去予約データ
        $previousReservations = Reservation::where('import_at', $previousImportAt)->get();

        // 配列ではなくキー比較にする
        $latestKeyed = $latestReservations->keyBy(function ($r) {
            return $r->visit_date . '_' . $r->patient_id . '_' . $r->reservation_content;
        });
        $previousKeyed = $previousReservations->keyBy(function ($r) {
            return $r->visit_date . '_' . $r->patient_id . '_' . $r->reservation_content;
        });

        // latestにしかないキーを算出
        $addedDiffs = $latestKeyed->diffKeys($previousKeyed);
        // previousにしかないキーを算出
        $deletedDiffs = $previousKeyed->diffKeys($latestKeyed);

        // セレクトボックスの選択肢
        $importDates = Reservation::where('import_at', '<', $latestImportAt)
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');

    return view('index', [
            'latestImportAt'     => $latestImportAt,
            'previousImportAt'   => $previousImportAt,
            'latestReservations' => $latestReservations,
            'addedDiffs'         => $addedDiffs,
            'deletedDiffs'       => $deletedDiffs,
            'importDates'        => $importDates,
            'latestDetectedAt'   => null, // checkでは未使用
        ]);

    }


}