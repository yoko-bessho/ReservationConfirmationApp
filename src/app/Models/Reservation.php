<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_at',
        'visit_date',
        'patient_id',
        'patient_name',
        'reservation_content',
    ];

    protected $casts = [
        'import_at' => 'datetime',
        'previous_import_at' => 'date',
        'current_import_date' => 'date',
        'visit_date' => 'date',
    ];

    public static function getLatestImportData()
    {
        $latestImportAt = Reservation::max('import_at');
        $latestImportData = Reservation::where('import_at', $latestImportAt)->get();

        return [
            'import_at' => $latestImportAt,
            'reservations' => $latestImportData,
        ];
    }


    public static function getPreviousImportData()
    {
        $latestImportAt = Reservation::max('import_at');

        if (!$latestImportAt) {
            return null;
        }
        // デフォルトではlatest_atの直前のimport_atを取得
        $previousImportAt = Reservation::where('import_at', '<', $latestImportAt)->max('import_at');

        if (!$previousImportAt) {
            return null;
        }

        return [
            'import_at' => $previousImportAt,
            'reservations' => Reservation::where('import_at', $previousImportAt)->get(),
        ];
    }
}