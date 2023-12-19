<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <main>
        <h1>機械学習用のデータの保存が完了</h1>
        <h2>
            市町村
        </h2>
        <div>
            {{ $city->name }}
        </div>
        <h2>
            ファイル名
        </h2>
        <div>
            {{ $city_csv_file->file_name }}
        </div>
    </main>
    <div>
        <a href="{{ route('info.get') }}">戻る</a>
    </div>
    
</body>
</html>