<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Models\Prefecture;

use App\Services\CityCsvFilesService;
use Illuminate\Database\Eloquent\Model;

// 機械学習実行用のコントローラー
class EexcDeepLearningController extends Controller
{

    public function execDeepLearning()
    {
        // $url = 'http://host.docker.internal:8888/api/v1/chintai_app/?data=2&data=52&data=40&data=55&data=0&data=1&data=0&data=0&data=0&data=0';
        $url = 'http://host.docker.internal:8888/api/v1/chintai_app/';
        $city_id = 32;

        // 送信用データの配列を作成
        $data = [2, 52, 40, 55];

        // 市町村と紐づく一番新しいcsvファイルを取得
        $csv_file = $this->getNewCsvFileName($city_id);
        
        #送られてきたプランを取得
        $plan = 'LDK';
        $params = [
            'file_name' => $csv_file->file_name,
            'data' => array(
                "$data[0]"=>'2',
                "$data[1]"=>'52',
                "$data[2]"=>'40',
                "$data[3]"=>'55',
            ),
            'plan' => $plan
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public function getNewCsvFileName(int $city_id): Model
    {
        $csv_file = CityCsvFilesService::getNewCsvFileName($city_id);
        return $csv_file;
    }

}
