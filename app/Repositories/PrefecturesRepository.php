<?php

namespace App\Repositories;

use App\Models\Prefecture;
use Illuminate\Database\Eloquent\Collection;

class PrefecturesRepository
{
    // 都道府県データを全て取得する
    public static function selectAllPrefectures(): Collection
    {
        $all_prefectures = Prefecture::all();
        return $all_prefectures;
    }

}