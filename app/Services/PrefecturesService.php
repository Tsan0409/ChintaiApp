<?php
namespace App\Services;

use App\Repositories\PrefecturesRepository;
use Illuminate\Database\Eloquent\Collection;

class PrefecturesService
{

    // 都道府県データを全て取得する
    public static function selectAllPrefectures(): Collection
    {
        $all_prefectures = PrefecturesRepository::selectAllPrefectures();
        return $all_prefectures;
    }

}