<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;
use Illuminate\Contracts\View\View;

class GetInfoFetchCsvController extends Controller
{
    private $prefectures_service;
    private $cities_service;
    private $city_csv_files_service;

    public function __construct(PrefecturesService $prefectures_service ,CitiesService $cities_service,CityCsvFilesService $city_csv_files_service)
    {
        $this->prefectures_service = $prefectures_service;
        $this->cities_service = $cities_service;
        $this->city_csv_files_service = $city_csv_files_service;
    }
    
    // 登録用フォームを作成
    public function getInfoForRegister(): View
    {
        $all_prefectures = $this->prefectures_service->selectAllPrefectures();
        return view('admin/get_info_for_register', [
            'prefectures' => $all_prefectures
        ]);
    }

    // 更新用フォーム作成
    public function getInfoForUpdate(): View
    {
        $prefectures_has_cities = $this->prefectures_service->selectPrefectureHasCities();
        $cities = $this->cities_service->selectCitiesByPrefecture($prefectures_has_cities->first()->id);
        $first_city = $cities->first()->id;
        $city_csv_file_by_city = $this->city_csv_files_service->selectNewCsvFileName($first_city);
        
        return view('admin/get_info_for_update', [
            'prefectures' => $prefectures_has_cities, 
            'cities' => $cities,
            'city_csv_file' => $city_csv_file_by_city
        ]);
    }
}
