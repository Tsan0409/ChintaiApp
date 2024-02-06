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

    private $prefectures_service;
    private $cities_service;
    private $city_csv_files_service;

    public function __construct(PrefecturesService $prefectures_service ,CitiesService $cities_service,CityCsvFilesService $city_csv_files_service) {
        $this->prefectures_service = $prefectures_service;
        $this->cities_service = $cities_service;
        $this->city_csv_files_service = $city_csv_files_service;
    }

    public function getInfoDeepLearning(): View
    {
        $prefectures_has_cities = $this->prefectures_service->selectPrefectureHasCities();
        $cities = $this->cities_service->selectCitiesByPrefecture($prefectures_has_cities->first()->id);
        $first_city = $cities->first()->id;
        $city_csv_files = $this->city_csv_files_service->selectNewCsvFileName($first_city);
        $room_plans = $this->city_csv_files_service->selectRoomPlans($first_city);
        
        return view('form_deeplearning', [
            'prefectures' => $prefectures_has_cities,
            'cities' => $cities,
            'csv_files' => $city_csv_files,
            'room_plans' => $room_plans
        ]);

    }
}