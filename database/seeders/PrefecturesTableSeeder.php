<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Prefecture;


// 都道府県のデータを挿入
class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prefectures = [
            ['name' => '北海道', 'kana_name' => 'ほっかいどう'],
            ['name' => '青森', 'kana_name' => 'あおもり'],
            ['name' => '岩手', 'kana_name' => 'いわて'],
            ['name' => '宮城', 'kana_name' => 'みやぎ'],
            ['name' => '秋田', 'kana_name' => 'あきた'],
            ['name' => '山形', 'kana_name' => 'やまがた'],
            ['name' => '福島', 'kana_name' => 'ふくしま'],
            ['name' => '茨城', 'kana_name' => 'いばらき'],
            ['name' => '栃木', 'kana_name' => 'とちぎ'],
            ['name' => '群馬', 'kana_name' => 'ぐんま'],
            ['name' => '埼玉', 'kana_name' => 'さいたま'],
            ['name' => '千葉', 'kana_name' => 'ちば'],
            ['name' => '東京', 'kana_name' => 'とうきょう'],
            ['name' => '神奈川', 'kana_name' => 'かながわ'],
            ['name' => '新潟', 'kana_name' => 'にいがた'],
            ['name' => '富山', 'kana_name' => 'とやま'],
            ['name' => '石川', 'kana_name' => 'いしかわ'],
            ['name' => '福井', 'kana_name' => 'ふくい'],
            ['name' => '山梨', 'kana_name' => 'やまなし'],
            ['name' => '長野', 'kana_name' => 'ながの'],
            ['name' => '岐阜', 'kana_name' => 'ぎふ'],
            ['name' => '静岡', 'kana_name' => 'しずおか'],
            ['name' => '愛知', 'kana_name' => 'あいち'],
            ['name' => '三重', 'kana_name' => 'みえ'],
            ['name' => '滋賀', 'kana_name' => 'しが'],
            ['name' => '京都', 'kana_name' => 'きょうと'],
            ['name' => '大阪', 'kana_name' => 'おおさか'],
            ['name' => '兵庫', 'kana_name' => 'ひょうご'],
            ['name' => '奈良', 'kana_name' => 'なら'],
            ['name' => '和歌山', 'kana_name' => 'わかやま'],
            ['name' => '鳥取', 'kana_name' => 'とっとり'],
            ['name' => '島根', 'kana_name' => 'しまね'],
            ['name' => '岡山', 'kana_name' => 'おかやま'],
            ['name' => '広島', 'kana_name' => 'ひろしま'],
            ['name' => '山口', 'kana_name' => 'やまぐち'],
            ['name' => '徳島', 'kana_name' => 'とくしま'],
            ['name' => '香川', 'kana_name' => 'かがわ'],
            ['name' => '愛媛', 'kana_name' => 'えひめ'],
            ['name' => '高知', 'kana_name' => 'こうち'],
            ['name' => '福岡', 'kana_name' => 'ふくおか'],
            ['name' => '佐賀', 'kana_name' => 'さが'],
            ['name' => '長崎', 'kana_name' => 'ながさき'],
            ['name' => '熊本', 'kana_name' => 'くまもと'],
            ['name' => '大分', 'kana_name' => 'おおいた'],
            ['name' => '宮崎', 'kana_name' => 'みやざき'],
            ['name' => '鹿児島', 'kana_name' => 'かごしま'],
            ['name' => '沖縄', 'kana_name' => 'おきなわ'],
        ];

        foreach ($prefectures as $prefecture) {
            Prefecture::create($prefecture);
        };
    }
}