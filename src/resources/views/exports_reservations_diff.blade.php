<table>
    <tr>
        <th width="10">予約日</th>
        <th width="10">患者ID</th>
        <th width="10">患者名</th>
        <th width="20">予約内容</th>
        <th>差分</th>
    </tr>

    @foreach ($latestReservations as $reservation)
        @php
            $key = $reservation->visit_date
                . '_' . $reservation->patient_id
                . '_' . $reservation->reservation_content;
        @endphp

        <tr>
            <td>{{ \Carbon\Carbon::parse($reservation->visit_date)->format('Y/m/d') }}</td>
            <td>{{ $reservation->patient_id }}</td>
            <td>{{ $reservation->patient_name }}</td>
            <td>{{ $reservation->reservation_content }}</td>
            <td>
                {{ $addedDiffs->has($key) ? '追加' : '' }}
            </td>
        </tr>
    @endforeach
</table>
