<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約状況管理</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header-utilities">
            <div class="header__title">
                <a href="/">予約状況管理アプリ</a>
            </div>

            <div class="header-nav">
                <ul>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="{{ route('import') }}">Excelインポート</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__link" href="{{ route('index') }}">予約状況確認</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>