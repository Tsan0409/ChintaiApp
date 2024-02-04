<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FetchCsvByRegisterPostRequest;
use App\Http\Requests\FetchCsvByUpdatePostRequest;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;
use App\Services\PrefecturesService;
use App\Traits\GetJsonData;
use App\Traits\KanaToRoma;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

// 機械学習用データ取得クラス
class ExecFetchCsvController extends Controller
{
    use KanaToRoma;
    use GetJsonData;

    private $api_url;
    private $prefectures_service;
    private $cities_service;
    private $city_csv_files_service;

    public function __construct(PrefecturesService $prefectures_service ,CitiesService $cities_service,CityCsvFilesService $city_csv_files_service) {
        $this->api_url = 'http://host.docker.internal:8888/api/v1/chintai_app/get_deepl_data/';
        $this->prefectures_service = $prefectures_service;
        $this->cities_service = $cities_service;
        $this->city_csv_files_service = $city_csv_files_service;
    }

    // 送られたデータを登録
    public function fetchCsvByRegister(FetchCsvByRegisterPostRequest $request): View
    {

        $prefecture = $this->prefectures_service->selectPrefecture($request->prefecture_id);
        $scraping_url = $request->scraping_url;
        $city_name = $request->city_name;
        $city_kana_name = $request->city_kana_name;
        
        // カナ名をローマ字に変換する
        $prefecture_roma_name = $this->kanaToRoma($prefecture->kana_name);
        $city_roma_name = $this->kanaToRoma($city_kana_name);

        // csvファイルを命名する
        $today = date('Ymd') ;
        $csv_file_name = "{$today}_{$prefecture_roma_name}_{$city_roma_name}.csv";

        // apiに送るデータを連想配列に入れる
        $params = [
            'url' => $scraping_url,
            'csv_name' => $csv_file_name
        ];

        // api呼び出し  
        $response_json = $this->get_json_data($params, $this->api_url);
        // jsonを連想配列に変更

        $response_array = json_decode($response_json, true);

        // 文字列を配列に変換
        $plan_list = str_replace("'", '"', $response_array['plan_list']);
        $room_plan = json_decode($plan_list, true);

        // 市町村データを保存
        $city = $this->insertCity($city_name, $city_kana_name, $prefecture);

        // 取得データを保存
        $city_csv_file = $this->insertCityCsvFiles($city, $csv_file_name, $room_plan);

        return view('admin/get_info_complete', [
            'city' => $city,
            'city_csv_file' => $city_csv_file
        ]);
    }

    // 送られたデータを更新
    public function fetchCsvByUpdate(FetchCsvByUpdatePostRequest $request): View
    {

        $prefecture = $this->prefectures_service->selectPrefecture($request->prefecture_id);
        $scraping_url = $request->scraping_url;
        $city = $this->cities_service->selectCityByCityId($request->city_id);

        // カナ名をローマ字に変換する
        $prefecture_roma_name = $this->kanaToRoma($prefecture->kana_name);
        $city_roma_name = $this->kanaToRoma($city->kana_name);

        // csvファイルを命名する
        $today = date('Ymd') ;
        $csv_file_name = "{$today}_{$prefecture_roma_name}_{$city_roma_name}.csv";

        // apiに送るデータを連想配列に入れる
        $params = [
            'url' => $scraping_url,
            'csv_name' => $csv_file_name
        ];

        // api呼び出し  
        $response_json = $this->get_json_data($params, $this->api_url);
        $response_array = json_decode($response_json, true);

        // 文字列を配列に変換
        $plan_list = str_replace("'", '"', $response_array['plan_list']);
        $room_plan = json_decode($plan_list, true);

        // 取得データを追加
        $city_csv_file = $this->insertCityCsvFiles($city, $csv_file_name, $room_plan);

        return view('admin/get_info_complete', [
            'city' => $city,
            'city_csv_file' => $city_csv_file
        ]);
    }

    // 市町村データの保存
    public function insertCity(string $city_name, string $city_kana_name, Model $prefecture): Model
    {
        $city = $this->cities_service->insertCity($city_name, $city_kana_name, $prefecture->id);
        return $city;
    }
    
    // 取得データの保存
    public function insertCityCsvFiles(Model $city, string $csv_file_name, array $room_plan): Model
    {
        $csv_file = $this->city_csv_files_service->insertCityCsvFile($csv_file_name, $city, $room_plan);
        return $csv_file;
    }



}