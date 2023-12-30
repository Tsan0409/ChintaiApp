<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}"></script>
    <title>賃貸料金調査アプリ</title>
</head>
<body>
    <main>
        {{ $slot }}
    </main>
</body> 
<footer>
    <a href="{{ route('home') }}">ホーム画面 </a>
</footer>

</html>