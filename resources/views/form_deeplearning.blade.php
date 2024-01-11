<x-layouts.chintai-app>
    <h1>都道府県と市町村を選択する</h1>
    <form action="{{ route('deepLearning.exec') }}" method="POST">
        @csrf
        <div class="prefectures">   
            <h4>都道府県</h4>
            <select name="prefecture_id" id="prefecture_id">
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
        <div class="room_count">   
            <h4>部屋数</h4>
            <input type="text" name="room_count" value="{{ old('room_count') }}">部屋
        </div>
        <div class="room_area">   
            <h4>広さ(㎡)</h4>
            <input type="text" name="room_area" value="{{ old('room_area') }}">㎡
        </div>
        <div class="distance">   
            <h4>駅までの徒歩距離(分)</h4>
            <input type="text" name="distance" value="{{ old('distance') }}">分
        </div>
        <div class="building_age">   
            <h4>築年数(年)</h4>
            <input type="text" name="building_age" value="{{ old('room_area') }}">年
        </div>
        <div class="room_plans">   
            <h4>間取り</h4>
            @foreach ($room_plans as $k => $v )
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
        <br>
        <input type="submit" value="送信">
    </form>
</x-layouts.chintai-app>