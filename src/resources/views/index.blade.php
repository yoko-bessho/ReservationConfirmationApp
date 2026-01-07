@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1 class="page-title">予約状況確認</h1>

        <div class="">
            <H2>最終import日時</H2>
            <p>{{ \Carbon\Carbon::parse($latestImportAt)->format('Y年m月d日 H:i') }}</p>
            <p>データ件数:  {{ $reservations->count() }} 件</p>
        </div>

        <div class="reservation-list">
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
                    @foreach ($reservations as $reservation)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($reservation->visit_date)->format('Y/m/d') }}</td>
                        <td>{{ $reservation->patient_id }}</td>
                        <td>{{ $reservation->patient_name }}</td>
                        <td>{{ $reservation->reservation_content }}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
        </div>

    </div>

@endsection