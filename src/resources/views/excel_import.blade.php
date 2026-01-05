@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="page-title">薬品予約管理 Excelインポート画面
    </h1>

    <div class="alert">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <ol class="error-list">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ol>
        </div>
    @endif
    </div>

    <div>
        <h3 class="instructions">インポートする前に</h3>
        <ul>
            <li>Excelファイル（.xlsx または .xls）を選択してください</li>
            <li>ファイルには「来院日」「患者ID」「患者名」「予約内容」の4項目が必要です</li>
            <li>1行目はヘッダー行として扱われます</li>
            <li>全ての項目は必須入力です</li>
        </ul>
    </div>

    <form class="upload-area" action="{{ route('import') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div>
            <label for="file" class="btn btn-select">ファイルを選択</label>
            <input type="file" id="file" name="file" accept=".xlsx,.xls">
        </div>
        <div class="file-name" id="fileName"></div>
        <button type="submit" class="btn btn-submit" id="submitBtn">
            インポート実行
        </button>
    </form>
</div>
@endsection