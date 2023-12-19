<?php
namespace App\Services;

use App\Repositories\CitiesRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CitiesService
{
    // 全ての市町村データを取得する
    public static function selectAllCities(): Collection
    {
        $all_cities = CitiesRepository::selectAllCities();
        return $all_cities;
    }

    // 市町村データを保存する
    public static function insertCity(string $name, string $kana_name, string $prefecture_id): Model
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