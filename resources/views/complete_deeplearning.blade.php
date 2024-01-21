<x-layouts.chintai-app>

    <p>都道府県：{{ $prefecture_name }}</p>
    <p>市町村　：{{ $city_name }}</p>
    <p>部屋面積：{{ $room_area }}</p>㎡
    <p>築年数　：{{ $building_age }}年</p>
    <p>部屋数　：{{ $room_count }}部屋</p>
    <p>駅徒歩　：{{ $distance }}分</p>
    <p>間取り　：{{ $room_plan }}</p>

    <p>上記の条件の場合、賃料は・・・</p>
    <h1>約{{ $price }}万円と推測されます</h1>

    <a href="http://0.0.0.0/get_deeplearning">戻る</a>
</x-layouts.chintai-app>