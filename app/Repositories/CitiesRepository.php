<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CitiesRepository
{
    // 指定した都道府県の市町村データを取得する
    public static function selectCitiesByPrefecture(int $prefecture_id): Collection
    {
        $cities = City::where('prefecture_id', '=', "{$prefecture_id}")->get();
        return $cities;
    }

    // 市町村データを全て取得する
    public static function selectAllCities(): Collection
    {
        $all_cities = City::all();
        return $all_cities;
    }

    // 市町村idからデータを取得する
    public static function selectCityByCityId(int $city_id): Model
    {
        $city = City::find($city_id);
        return $city;
    }

    // 市町村データを登録
    public static function insertCity(array $params): Model
    {
        $city = City::create($params);
        return $city;
    }
}