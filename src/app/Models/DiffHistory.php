<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

class DiffHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'detected_at',
        'previous_import_at',
        'current_import_date',
        'diff_type',
        'data_import_at',
        'visit_date',
        'patient_id',
        'patient_name',
        'reservation_content',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'previous_import_at' => 'date',
        'current_import_date' => 'date',
        'data_import_at' => 'datetime',
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