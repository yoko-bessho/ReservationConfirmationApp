<?php


namespace App\Imports;

use App\Services\ImportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ReservationImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    public function collection(Collection $rows)
    {
        $this->importService->processRows($rows);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
