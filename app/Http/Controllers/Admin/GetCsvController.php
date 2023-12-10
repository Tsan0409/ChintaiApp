<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CityCsvFile;
use Illuminate\Http\Request;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;

// Csvファイル作成クラス
class GetCsvController extends Controller
{

    var $prefecture_id = '27';
    var $city_name = '高槻';
    var $city_kana_name = 'たかつき';
    var $csv_file_name = '20231205Osaka_Takatsuki.csv';
    var $scraping_url = 'https://suumo.jp/jj/chintai/ichiran/FR301FC001/?ar=060&bs=040&ta=27&sc=27207&cb=0.0&ct=9999999&et=9999999&cn=9999999&mb=0&mt=9999999&shkr1=03&shkr2=03&shkr3=03&shkr4=03&fw2=';
    var $room_plan = ['DK', 'LDK', 'K', 'R'];

    
    public function getCsv(){

        // apiでデータを取得する
        $api_url = 'http://host.docker.internal:8888/api/v1/chintai_app/get_deepl_data/';
        $params = [
            'url' => $this->scraping_url,
            'csv_name' => $this->csv_file_name
        ];  

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);

        // 市町村データを保存
        $city = $this->insertCity();

        // 取得データを保存
        $city_csv_file = $this->insertCityCsvFiles($city);

        $AllRes = [$res, $city, $city_csv_file];
        return $AllRes;
    }

    // 市町村データの保存
    public function insertCity()
    {
        $city = CitiesService::insertCity($this->city_name, $this->city_kana_name, $this->prefecture_id);
        return $city;
    }
    
    // 取得データの保存
    public function insertCityCsvFiles($city)
    {
        $csv_file = CityCsvFilesService::insertCityCsvFile($this->csv_file_name, $city, $this->room_plan);
        return $csv_file;
    }

    
}