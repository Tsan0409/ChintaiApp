<x-layouts.chintai-app>
    <h1>機械学習用データを登録する</h1>
    @if ($errors->any())
    <div style="color:red">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{  $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('csv.get.register') }}" method="POST">
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
        <div class="scraping_url">
            <h3>スクレイピング用のURL</h3>
            <div class="sample">
                https://suumo.jp/jj/chintai/ichiran/FR301FC001/?ar=060&bs=040&ta=27&sc=27207&cb=0.0&ct=9999999&et=9999999&cn=9999999&mb=0&mt=9999999&shkr1=03&shkr2=03&shkr3=03&shkr4=03&fw2=
            </div>
            <input type="text" name="scraping_url" value="{{ old('scraping_url') }}">
        </div>
        <input type="submit" value="送信">
    </form>
</x-layouts.chintai-app>