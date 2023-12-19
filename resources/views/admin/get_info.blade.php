<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <main>
        <h1>機械学習用データを取得する</h1>
        <form action="{{ route('csv.get') }}" method="POST">
            @csrf
            <div class="prefectures">   
                <h3>都道府県</h3>
                <select name="prefecture_id" id="">
                    @foreach ($prefectures as $prefecture )
                        <option value="{{ $prefecture->id }}" @selected($prefecture->id == old('prefecture_id'))>
                            {{ $prefecture->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="city">
                <h3>市町村名</h3>
                <div class="name">
                    <label for="">漢字</label>
                    <input type="text" name="city_name" id="" value="{{ old('city_name') }}">
                </div>
                <div class="kana_name">
                    <label for="">かな</label>
                    <input type="text" name="city_kana_name" id="" value="{{ old('city_kana_name') }}">
                </div>
            </div>
            <div class="csv_file">
                <h3>ファイル名</h3>
                <div class="sample">
                    20231205Osaka_Takatsuki.csv
                </div>
                <input type="text" name="csv_file_name" value="{{ old('csv_file_name') }}">
            </div>
            <div class="scraping_url">
                <h3>スクレイピング用のURL</h3>
                <div class="sample">
                    https://suumo.jp/jj/chintai/ichiran/FR301FC001/?ar=060&bs=040&ta=27&sc=27207&cb=0.0&ct=9999999&et=9999999&cn=9999999&mb=0&mt=9999999&shkr1=03&shkr2=03&shkr3=03&shkr4=03&fw2=
                </div>
                <input type="text" name="scraping_url" value="{{ old('scraping_url') }}">
            </div>
            <input type="submit" value="送信">
        </form>
    </main>
    
</body>
</html>