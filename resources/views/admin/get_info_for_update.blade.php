<x-layouts.chintai-app>
    <h1>機械学習用データを更新する</h1>
    @if(Session::has('error'))
    <div style="color:red" class="alert alert-danger">
        {{ Session::get('error') }}
    </div>
    @endif
    @if ($errors->any())
    <div style="color:red">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{  $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('csv.get.update') }}" method="POST">
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
        <div class="cities">   
            <h4>市町村</h4>
            <select name="city_id" id="city_id">
                @foreach ($cities as $city )
                    <option value="{{ $city->id }}" @selected($city->id == old('city_id'))>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="scraping_url">
            <h3>スクレイピング用のURL</h3>
            <div class="sample">
                サンプル：https://suumo.jp/jj/chintai/ichiran/FR301FC001/?ar=060&bs=040&ta=27&sc=27207&cb=0.0&ct=9999999&et=9999999&cn=9999999&mb=0&mt=9999999&shkr1=03&shkr2=03&shkr3=03&shkr4=03&fw2=
            </div>
            <input type="text" name="scraping_url" value="{{ old('scraping_url') }}">
        </div>
        <input type="submit" value="送信">
    </form>
</x-layouts.chintai-app>