<?php

namespace App\Repositories;

use App\Models\CityCsvFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CityCsvFilesRepository
{

    // 機械学習用データを全て取得する
    public static function selectAllCityCsvFiles(): Collection
    {
        $all_csv_files = CityCsvFile::all();
        return $all_csv_files;
    }

    // 機械学習用データを登録
    public static function insertCityCsvFile(array $params): Model
    {
        $city_csv_file= CityCsvFile::create($params);
        return $city_csv_file;
    }

    // 市町村番号から最新のcsvファイル名を取得する
    public static function getNewCsvFileName(int $city_id): Model
    {   
        $csv_file = CityCsvFile::orderBy('created_at')->where('city_id', '=', $city_id)->first();
        return $csv_file;
    }

}