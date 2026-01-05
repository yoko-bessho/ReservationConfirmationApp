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
        'import_date' => 'date',
        'visit_date' => 'date',
    ];

    // 任意のインポート日のデータ取得
    public static function getByImportDate(string $importDate)
    {
        return self::where('import_date', $importDate)->get();
    }

    // 最新のインポート日を取得
    public static function getLatestImportDate(): ?string
    {
        return self::max('import_date');
    }

    // 指定した日付より前の最新のインポート日を取得
    public static function getPreviousImportDate(): ?string
    {
        $latestDate = self::getLatestImportDate();
        
        if (!$latestDate) {
            return null;
        }
        
        return self::where('import_date', '<', $latestDate)
            ->max('import_date');
    }

}