@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="reservation-info">
        <H2>最新インポート日時</H2>

        @if ($latestImportAt)
        <p>{{ \Carbon\Carbon::parse($latestImportAt)->format('Y年m月d日 H:i') }}</p>
        @else
        <p>インポートされた予約はありません</p>
        @endif
        <p>データ件数:  {{ $latestReservations->count() }} 件</p>

        <div class="reservation-list">
            <h3>最新の予約一覧</h3>
            <table class="table">
                <tr>
                    <th>予約日</th>
                    <th>患者ID</th>
                    <th>患者名</th>
                    <th>予約内容</th>
                </tr>
                @foreach ($latestReservations as $latestReservation)
                    @php
                    $key = $latestReservation->visit_date
                        . '_' . $latestReservation->patient_id
                        . '_' . $latestReservation->reservation_content;
                    @endphp
                    <tr class="list {{ $addedDiffs->has($key) ? 'highlight-added' : '' }}">
                        <td>{{ \Carbon\Carbon::parse($latestReservation->visit_date)->format('Y/m/d') }}</td>
                        <td>{{ $latestReservation->patient_id }}</td>
                        <td>{{ $latestReservation->patient_name }}</td>
                        <td>{{ $latestReservation->reservation_content }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>


    <div class="reservation-difference">
        <div class="diff-form">
            <h3>予約追加確認はこちら</h3>

            <p>
                最新：
                <strong>
                    {{ \Carbon\Carbon::parse($latestImportAt)->format('Y/m/d H:i') }}
                </strong>
            </p>
        <p>
        比較対象日： <strong>{{ $previousImportAt }}</strong></p>
            <form method="GET" action="{{ route('diff.check') }}">
                <div>
                    <label>比較対象日選択</label>
                    <select name="from_import_at" required>
                        @foreach ($importDates as $date)
                            <option value="{{ $date }}">
                                {{ \Carbon\Carbon::parse($date)->format('Y/m/d H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit">予約追加状況チェック</button>
            </form>
        </div>

        <div class="reservation-list">
            <h3>追加された予約</h3>

            @if ($addedDiffs->isEmpty())
            <p>追加された予約はありません。</p>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>予約日</th>
                        <th>患者ID</th>
                        <th>患者名</th>
                        <th>予約内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addedDiffs as $diff)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($diff->visit_date)->format('Y/m/d') }}</td>
                        <td>{{ $diff->patient_id }}</td>
                        <td>{{ $diff->patient_name }}</td>
                        <td>{{ $diff->reservation_content }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="reservation-list">
            <h3>削除された予約</h3>

            @if ($deletedDiffs->isEmpty())
            <p>削除された予約はありません。</p>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th>予約日</th>
                        <th>患者ID</th>
                        <th>患者名</th>
                        <th>予約内容</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deletedDiffs as $diff)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($diff->visit_date)->format('Y/m/d') }}</td>
                        <td>{{ $diff->patient_id }}</td>
                        <td>{{ $diff->patient_name }}</td>
                        <td>{{ $diff->reservation_content }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
    
</div>

@endsection