<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

use App\services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;


// 機械学習実行用のフォーム作成
class GetInfoDeepLearningController extends Controller
{
    private $all_prefectures;
    private $cities;
    private $city_csv_files;
    private $room_plans;

    public function __construct() {
        $this->all_prefectures = PrefecturesService::selectAllPrefectures();
        $this->cities = CitiesService::selectCitiesByPrefecture($this->all_prefectures->first()->id);
        $this->city_csv_files = CityCsvFilesService::selectNewCsvFileName($this->cities->first()->id);
        $this->room_plans = CityCsvFilesService::selectRoomPlans($this->cities->first()->id);
    }

    public function getInfoDeepLearning(): View
    {
        
        return view('form_deeplearning', [
            'prefectures' => $this->all_prefectures, 
            'cities' => $this->cities,
            'csv_files' => $this->city_csv_files,
            'room_plans' => $this->room_plans
        ]);

    }
}