<?php

namespace App\Repositories;

use App\Models\CityCsvFile;

class CityCsvFilesRepository
{
    // 市町村データを登録
    public static function insertCityCsvFile(array $params): CityCsvFile
    {
        $cityCsvFile = CityCsvFile::create($params);
        return $cityCsvFile;
    }
}