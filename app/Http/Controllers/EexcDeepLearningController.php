<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExecDeepLearningPostRequest;
use App\Services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;
use App\Traits\GetJsonData;
use App\Traits\KanaToRoma;
use Illuminate\Database\Eloquent\Model;

// 機械学習実行用のコントローラー
class EexcDeepLearningController extends Controller
{

    use GetJsonData;
    use KanaToRoma;

    private $api_url;
    private $prefectures_service;
    private $cities_service;
    private $city_csv_files_service;

    public function __construct(PrefecturesService $prefectures_service ,CitiesService $cities_service,CityCsvFilesService $city_csv_files_service) {
        $this->api_url = 'http://host.docker.internal:8888/api/v1/chintai_app/';
        $this->prefectures_service = $prefectures_service;
        $this->cities_service = $cities_service;
        $this->city_csv_files_service = $city_csv_files_service;
    }

    public function execDeepLearning(ExecDeepLearningPostRequest $request)
    {
        $prefecture_id = $request->prefecture_id;
        $city_id = $request->city_id;
        $room_count = $request->room_count;
        $room_area = $request->room_area;
        $distance = $request->distance;
        $building_age = $request->building_age;
        $room_plan = $request->room_plan;

        // 送信用データの配列を作成
        $data = [$room_count, $room_area, $distance, $building_age];

        // 市町村と紐づく一番新しいcsvファイルを取得
        $csv_file = $this->getNewCsvFileName($city_id);

        # POST用のパラメータを作成
        $params = [
            'file_name' => $csv_file->file_name,
            'data' => array(
                "$data[0]"=>"{$room_count}",
                "$data[1]"=>"{$room_area}",
                "$data[2]"=>"{$distance}",
                "$data[3]"=>"{$building_age}",
            ),
            'plan' => $room_plan
        ];

        // apiを叩く
        $response_json = $this->get_json_data($params, $this->api_url);
        $response_float = floatval($response_json);

        // 都道府県名を取得する
        $prefecture_name = $this->prefectures_service->selectPrefecture($prefecture_id)->name;
        $city_name = $this->cities_service->selectCityByCityId($city_id)->name;

        $rounded_price = round($response_float, 1);

        return view('complete_deeplearning', [
            'prefecture_name' => $prefecture_name,
            'city_name' => $city_name,
            'room_area' => $room_area,
            'building_age' => $building_age,
            'room_count' => $room_count,
            'distance' => $distance,
            'room_plan' => $room_plan,
            'price' => $rounded_price
        ]);
    }

    public function getNewCsvFileName(int $city_id): Model
    {
        $csv_file = $this->city_csv_files_service->selectNewCsvFileName($city_id);
        return $csv_file;
    }

}
