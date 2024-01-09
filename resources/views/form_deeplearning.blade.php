<x-layouts.chintai-app>
    <h1>都道府県と市町村を選択する</h1>
    <form action="{{ route('deepLearning.exec') }}" method="POST">
        @csrf
        <div class="prefectures">   
            <h3>都道府県</h3>
            <select name="prefecture_id" id="prefecture_id">
                @foreach ($prefectures as $prefecture )
                    <option value="{{ $prefecture->id }}" @selected($prefecture->id == old('prefecture_id'))>
                        {{ $prefecture->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="cities">   
            <h3>市町村</h3>
            <select name="city_id" id="city_id">
                @foreach ($cities as $city )
                    <option value="{{ $city->id }}" @selected($city->id == old('city_id'))>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="room_count">   
            <h3>部屋数</h3>
            <input type="text" class="room_count" value="{{ old('room_count') }}">部屋
        </div>
        <div class="room_area">   
            <h3>広さ(㎡)</h3>
            <input type="text" class="room_area" value="{{ old('room_area') }}">㎡
        </div>
        <div class="distance">   
            <h3>駅までの徒歩距離(分)</h3>
            <input type="text" class="room_area" value="{{ old('distance') }}">分
        </div>
        <div class="building_age">   
            <h3>築年数(年)</h3>
            <input type="text" class="room_area" value="{{ old('room_area') }}">年
        </div>
        <div class="room_plans">   
            <h3>間取り</h3>
            {{ $csv_files}}
            @foreach ($room_plans as $k => $v )
            {{ $k }}と{{ $v }}
            @endforeach

            <select name="room_plan" id="room_plan">
                @foreach ($room_plans as $room_plan => $value )
                    @if ($value == 1)
                    <option value="{{ $room_plan }}" @selected($city->id == old('room_plan'))>
                        {{ $room_plan }}
                    </option>
                    @endif
                @endforeach
            </select>
        </div>


        <input type="submit" value="送信">
    </form>
</x-layouts.chintai-app>