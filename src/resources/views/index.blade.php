@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">
@endsection

@section('content')

<div id="app"></div>

{{-- Vite dev server (Laravel 8 では @vite ディレクティブ非対応のため直接参照) --}}
<script type="module">
    import RefreshRuntime from 'http://localhost:5173/@react-refresh'
    RefreshRuntime.injectIntoGlobalHook(window)
    window.$RefreshReg$ = () => {}
    window.$RefreshSig$ = () => () => {}
    window.__vite_plugin_react_preamble_installed__ = true
</script>
<script type="module" src="http://localhost:5173/@vite/client"></script>
<script type="module" src="http://localhost:5173/resources/ts/App.tsx"></script>


@endsection