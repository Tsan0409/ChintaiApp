<?php

namespace App\Repositories;

use App\Models\City;

class CitiesRepository
{
    // 市町村データを登録
    public static function insertCity(array $params): City
    {
        $city = City::create($params);
        return $city;
    }
}