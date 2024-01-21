<?php
return [
    // エラーメッセージを設定
    'exists' => '正しい :attribute を選択してください。',
    'max' => [
        'numeric' => ':attribute は :max 以下を入力してください',
        'string' => ':attribute は :max 文字以内を入力してください'
    ],
    'min' => [
        'numeric' => ':attribute は :min 以上を入力してください',
        'string' => ':attribute は :min 文字以上を入力してください'
    ],
    'numeric' => ':attribute は数値で入力してください',
    'required' => ':attribute は必須入力です',
    'unique' => ':attribute はすでに登録されています',
    'url' => ':attribute はURLを入力してください',
    'attributes' => [
        'prefecture_id' => '都道府県',
        'city_id' => '市町村',
        'scraping_url' => 'スクレイピング用のURL',
        'city_name' => '市町村名（漢字）',
        'city_kana_name' => '市町村名（かな）',
        'room_area' => '部屋面積',
        'building_age' => '築年数',
        'room_count' => '部屋数',
        'distance' => '駅までの距離',
    ],
];