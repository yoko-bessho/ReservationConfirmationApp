<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}