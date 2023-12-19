<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CitiesRepository
{
    // 市町村データを全て取得する
    public static function selectAllCities(): Collection
    {
        $all_cities = City::all();
        return $all_cities;
    }

    // 市町村データを登録
    public static function insertCity(array $params): Model
    {
        $city = City::create($params);
        return $city;
    }
}