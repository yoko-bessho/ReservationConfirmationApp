<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\DiffHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiffHistoryController extends Controller
{
    public function index()
    {
        $latestImportAt = Reservation::max('import_at');
        $previousImportAt = Reservation::where('import_at', '<', $latestImportAt)->max('import_at');

        $importDates = Reservation::select('import_at')
            ->distinct()
            ->orderBy('import_at', 'desc')
            ->pluck('import_at');


        $latest = DiffHistory::getLatestImportData();
        $previous = DiffHistory::getPreviousImportData();

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

        // latestにしかないキーを算出
        $added = $latestKeyed->diffKeys($previousKeyed);
        // previousにしかないキーを算出
        $deleted = $previousKeyed->diffKeys($latestKeyed);

// createの部分は今アクセスするたびチェックしているので、チェックタイミング決めて修正すること！！！⭐︎
        foreach ($added as $reservation) {
            DiffHistory::create([
                'detected_at' => now(),
                'previous_import_at' => $previous['import_at'] ?? null,
                'current_import_date' => Carbon::parse($latest['import_at'])->toDateString(),
                'diff_type' => 'added',
                'visit_date' => $reservation->visit_date,
                'patient_id' => $reservation->patient_id,
                'patient_name' => $reservation->patient_name,
                'reservation_content' => $reservation->reservation_content,
                'data_import_at' => $latest['import_at'],
            ]);
        }
        foreach ($deleted as $reservation) {
            DiffHistory::create([
                'detected_at' => now(),
                'previous_import_at' => $previous['import_at'] ?? null,
                'current_import_date' => Carbon::parse($latest['import_at'])->toDateString(),
                'diff_type' => 'deleted',
                'visit_date' => $reservation->visit_date,
                'patient_id' => $reservation->patient_id,
                'patient_name' => $reservation->patient_name,
                'reservation_content' => $reservation->reservation_content,
                'data_import_at' => $latest['import_at'],
            ]);
        }

        $latestDetectedAt = DiffHistory::max('detected_at');

        $latestDiffs = DiffHistory::where('detected_at', $latestDetectedAt)->get();

        $addedDiffs = $latestDiffs->where('diff_type', 'added');
        $deletedDiffs = $latestDiffs->where('diff_type', 'deleted');

        return view('index', compact(
            'latestReservations',
            'latestImportAt',
            'added',
            'deleted',
            'latestImportAt',
            'previousImportAt',
            'latestDetectedAt',
            'addedDiffs',
            'deletedDiffs',
            'importDates',
        ));
    }

    public function check(Request $request)
    {
        $from = $request->input('from_import_at');
        $to   =  Reservation::max('import_at');

        // 念のための安全チェック
        if (!$from || !$to) {
            return back()->withErrors('比較可能なデータがありません');
        }

        if ($from >= $to) {
            return back()->withErrors('比較元は最新より過去を選択してください');
        }

        dd($from, $to);
    }

}
