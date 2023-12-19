<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

// 機械学習用データ取得クラス
class GetCsvController extends Controller
{

    public $prefecture_id;
    public $city_name;
    public $city_kana_name;
    public $csv_file_name;
    public $scraping_url;
    public $room_plan;

    // フォームを作成
    public function getInfo()
    {
        $all_prefectures = PrefecturesService::selectAllPrefectures();
        $all_cities = CitiesService::selectAllCities();
        $all_csv_files = CityCsvFilesService::selectAllCityCsvFiles();

        return view('admin/get_info', [
                'prefectures' => $all_prefectures, 
                'cities' => $all_cities,
                'all_csv_files' => $all_csv_files
            ]);
    }

    // 送られたデータを処理
    public function getCsv(Request $request): View
    {
        // postデータを取得
        $this->prefecture_id = $request->prefecture_id;
        $this->city_name = $request->city_name;
        $this->city_kana_name = $request->city_kana_name;
        $this->csv_file_name = $request->csv_file_name;
        $this->scraping_url = $request->scraping_url;

        // apiに送るデータを変数に入れる
        $api_url = 'http://host.docker.internal:8888/api/v1/chintai_app/get_deepl_data/';
        $params = [
            'url' => $this->scraping_url,
            'csv_name' => $this->csv_file_name
        ];  

        // api呼び出し  
        $response_array = $this->postUrl($api_url, $params);

        // 文字列を配列に変換
        $plan_list = str_replace("'", '"', $response_array['plan_list']);
        $this->room_plan = json_decode($plan_list, true);

        // 市町村データを保存
        $city = $this->insertCity();

        // 取得データを保存
        $city_csv_file = $this->insertCityCsvFiles($city);

        return view('admin/get_info_complete', [
            'city' => $city,
            'city_csv_file' => $city_csv_file
        ]);
    }

        // apiでデータを取得するして連想配列で返す
    public function postUrl($api_url, $params): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // jsonを連想配列に変更
        $response_array = json_decode($response, true);
        return $response_array;
    }

    // 市町村データの保存
    public function insertCity(): Model
    {
        $city = CitiesService::insertCity($this->city_name, $this->city_kana_name, $this->prefecture_id);
        return $city;
    }
    
    // 取得データの保存
    public function insertCityCsvFiles($city): Model
    {
        $csv_file = CityCsvFilesService::insertCityCsvFile($this->csv_file_name, $city, $this->room_plan);
        return $csv_file;
    }

}