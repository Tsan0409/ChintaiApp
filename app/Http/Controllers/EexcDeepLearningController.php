<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;

use App\Http\Requests\ExecDeepLearningPostRequest;
use Illuminate\Database\Eloquent\Model;

// 機械学習実行用のコントローラー
class EexcDeepLearningController extends Controller
{

    private $url;

    public function __construct() {

        $this->url = 'http://host.docker.internal:8888/api/v1/chintai_app/';

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

        // => トレイトにまとめる
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = floatval(curl_exec($ch));
        curl_close($ch);

        // 都道府県名を取得する
        $prefecture_name = PrefecturesService::selectPrefecture($prefecture_id)->name;
        $city_name = CitiesService::selectCityByCityId($city_id)->name;

        $rounded_price = round($response, 1);

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
        $csv_file = CityCsvFilesService::selectNewCsvFileName($city_id);
        return $csv_file;
    }

}
