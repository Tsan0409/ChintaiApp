<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// 市町村データを挿入
class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            ['id'=> '1', 'name' => '京都市中京区', 'kana_name' => 'きょうとしなかぎょうく', 'prefecture_id' => '26', 'created_at' => '2024-02-15 01:47:35','updated_at' => '2024-02-15 01:47:35'],
            ['id' => '2', 'name' => '京都市上京区', 'kana_name' => 'きょうとしかみぎょうく', 'prefecture_id' => '26', 'created_at' => '2024-02-15 01:47:37','updated_at' => '2024-02-15 01:47:37'],
        ];

        foreach ($cities as $city) {
            City::create($city);
        };
    }
}