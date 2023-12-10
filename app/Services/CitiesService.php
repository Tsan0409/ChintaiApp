<?php
namespace App\Services;

use App\Repositories\CitiesRepository;
use App\Models\City;

class CitiesService
{
    public static function insertCity(string $name, string $kana_name, string $prefecture_id): City
    {
        $params = [
            'name' => $name,
            'kana_name' => $kana_name,
            'prefecture_id' => $prefecture_id
        ];

        $city = CitiesRepository::insertCity($params);
        return $city;
    }
}