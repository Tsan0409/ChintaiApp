<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

use App\Services\PrefecturesService;
use App\Services\CitiesService;

class GetInfoFetchCsvController extends Controller
{
        // フォームを作成
        public function getInfo(): View
        {
            $all_prefectures = PrefecturesService::selectAllPrefectures();
            $all_cities = CitiesService::selectAllCities();
    
            return view('admin/get_info', [
                    'prefectures' => $all_prefectures, 
                    'cities' => $all_cities
                ]);
        }
}
