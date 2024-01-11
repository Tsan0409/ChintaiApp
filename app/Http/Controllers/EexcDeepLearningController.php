<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PrefecturesService;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

// 機械学習実行用のコントローラー
class EexcDeepLearningController extends Controller
{

    private $url;
    private $prefecture_id;
    private $city_id;
    private $room_area;
    private $building_age;
    private $room_count;
    private $distance;
    private $room_plan;

    public function __construct(Request $request) {

        $this->url = 'http://host.docker.internal:8888/api/v1/chintai_app/';
        $this->prefecture_id = $request->prefecture_id;
        $this->city_id = $request->city_id;
        $this->room_count = $request->room_count;
        $this->room_area = $request->room_area;
        $this->distance = $request->distance;
        $this->building_age = $request->building_age;
        $this->room_plan = $request->room_plan;
    }

    public function execDeepLearning()
    {
        // 送信用データの配列を作成
        $data = [$this->room_count, $this->room_area, $this->distance, $this->building_age];

        // 市町村と紐づく一番新しいcsvファイルを取得
        $csv_file = $this->getNewCsvFileName($this->city_id);
        
        #送られてきたプランを取得

        # POST用のパラメータを作成
        $params = [
            'file_name' => $csv_file->file_name,
            'data' => array(
                "$data[0]"=>"{$this->room_count}",
                "$data[1]"=>"{$this->room_area}",
                "$data[2]"=>"{$this->distance}",
                "$data[3]"=>"{$this->building_age}",
            ),
            'plan' => $this->room_plan
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
        $prefecture_name = PrefecturesService::selectPrefecture($this->prefecture_id)->name;
        $city_name = CitiesService::selectCityByCityId($this->city_id)->name;

        $rounded_price = round($response, 1);

        return view('complete_deeplearning', [
            'prefecture_name' => $prefecture_name,
            'city_name' => $city_name,
            'room_area' => $this->room_area,
            'building_age' => $this->building_age,
            'room_count' => $this->room_count,
            'distance' => $this->distance,
            'room_plan' => $this->room_plan,
            'price' => $rounded_price
        ]);
    }

    public function getNewCsvFileName(int $city_id): Model
    {
        $csv_file = CityCsvFilesService::selectNewCsvFileName($city_id);
        return $csv_file;
    }

}
