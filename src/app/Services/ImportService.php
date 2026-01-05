<?php


namespace App\Services;

use App\Models\Reservation;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Collection;

class ImportService
{
    private Carbon $importDate;

    public function __construct()
    {
        $this->importDate = Carbon::now();
    }

    public function processRows(Collection $rows): void
    {
        foreach ($rows as $row) {
            Reservation::create([
                'import_at' => $this->importDate,
                'visit_date'  => Carbon::instance(
                    Date::excelToDateTimeObject($row['予約日'])
                ),
                'patient_id'  => $row['ID'],
                'patient_name'=> $row['氏名'],
                'reservation_content' => $row['予約内容'],
            ]);
        }
    }
}
