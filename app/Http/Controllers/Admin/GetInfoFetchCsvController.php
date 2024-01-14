<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Contracts\View\View;

use App\Services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;

class GetInfoFetchCsvController extends Controller
{
    private $all_prefectures;
    private $cities;
    private $city_csv_file;

    public function __construct()
    {
        $this->all_prefectures = PrefecturesService::selectAllPrefectures();
        $this->cities = CitiesService::selectCitiesByPrefecture($this->all_prefectures->first()->id);
        $this->city_csv_file = CityCsvFilesService::selectNewCsvFileName($this->cities->first()->id);
    }
    
    // 登録用フォームを作成
    public function getInfoForRegister(): View
    {
        return view('admin/get_info_for_register', [
            'prefectures' => $this->all_prefectures
        ]);
    }

    // 更新用フォーム作成
    public function getInfoForUpdate(): View
    {
        return view('admin/get_info_for_update', [
            'prefectures' => $this->all_prefectures, 
            'cities' => $this->cities,
            'city_csv_file' => $this->city_csv_file
        ]);
    }
}
