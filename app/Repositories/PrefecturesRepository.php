<?php

namespace App\Repositories;

use App\Models\Prefecture;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PrefecturesRepository
{
    // 都道府県データを全て取得する
    public static function selectAllPrefectures(): Collection
    {
        $all_prefectures = Prefecture::all();
        return $all_prefectures;
    }

    // 都道府県idからデータを取得する
    public static function selectPrefecture(int $prefecture_id): Model
    {
        $prefecture = Prefecture::find($prefecture_id);
        return $prefecture;
    }

    // 関連する市町村テーブルが存在する都道府県のみ取得
    public static function selectPrefectureHasCities(): Collection
    {
        $prefectures = Prefecture::has('cities', '>=', 1)->get();
        return $prefectures;
    }

}