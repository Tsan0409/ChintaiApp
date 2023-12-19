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
        $cityCsvFile = CityCsvFile::create($params);
        return $cityCsvFile;
    }
}