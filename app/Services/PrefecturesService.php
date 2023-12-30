<?php
namespace App\Services;

use App\Repositories\PrefecturesRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PrefecturesService
{

    // 都道府県データを全て取得する
    public static function selectAllPrefectures(): Collection
    {
        $all_prefectures = PrefecturesRepository::selectAllPrefectures();
        return $all_prefectures;
    }

    // 都道府県idからデータを取得する
    public static function selectPrefecture(int $prefecture_id): Model
    {
        $prefecture = PrefecturesRepository::selectPrefecture($prefecture_id);
        return $prefecture;
    }

}