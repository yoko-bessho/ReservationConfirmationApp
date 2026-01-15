<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ReservationDiffService
{
    /**
     * 2つの予約データを比較して差分を返す
     */
    public function calculate(
        Collection $latestReservations,
        Collection $previousReservations,
    ): array {
        $latestKeyed = $this->keyByReservation($latestReservations);
        $previousKeyed = $this->keyByReservation($previousReservations);

        return [
            'addedDiffs' => $latestKeyed->diffKeys($previousKeyed),
            'deletedDiffs' => $previousKeyed->diffKeys($latestKeyed),
        ];
    }

    /**
     * 予約を比較用キーでまとめる
     */
    private function keyByReservation(Collection $reservations): Collection
    {
        return $reservations->keyBy(fn ($r) =>
            $r->visit_date . '_' . $r->patient_id . '_' . $r->reservation_content
        );
    }
}
