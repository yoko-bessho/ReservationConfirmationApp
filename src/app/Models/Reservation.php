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
        'visit_date' => 'date',
    ];



    public static function getLatestImportAt(): ?Carbon
    {
        $value = self::max('import_at');
        return $value ? Carbon::parse($value) : null;
    }


    public static function getPreviousImportAt(): ?Carbon
    {
        $latest = self::getLatestImportAt();
        $value = self::where('import_at', '<', $latest)->max('import_at');
        return $value ? Carbon::parse($value) : null;
    }

}