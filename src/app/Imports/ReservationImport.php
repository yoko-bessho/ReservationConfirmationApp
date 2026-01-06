<?php

namespace App\Imports;

use App\Services\ImportService;
use App\Models\Reservation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ReservationImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    WithChunkReading
{
    use Importable;

    public function model(array $row)
    {
        return new Reservation([
            'import_at' => Carbon::now(),
            'visit_date'  => Date::excelToDateTimeObject($row['予約日'])->format('Y-m-d'),
            'patient_id'  => $row['id'],
            'patient_name'=> $row['氏名'],
            'reservation_content' => $row['予約内容'],
        ]);
    }


    public function rules(): array
    {
        return [
            '*.予約日' => 'required',
            '*.id' => 'required|max:10',
            '*.氏名' => 'required|max:50',
            '*.予約内容' => 'required|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '予約日.required' => '予約日は必須です',
            'id.required' => 'IDは必須です',
            'id.max' => 'IDは10桁以内で入力してください',
            '氏名.required' => '氏名は必須です',
            '氏名.max' => '氏名は100文字以内で入力してください',
            '予約内容.required' => '予約内容は必須です',
            '予約内容.max' => '予約内容は255文字以内で入力してください',
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
