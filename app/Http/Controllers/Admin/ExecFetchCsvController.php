<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;
use App\Services\PrefecturesService;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

// 機械学習用データ取得クラス
class ExecFetchCsvController extends Controller
{

    private $api_url;
    private $prefecture;
    private $city;
    private $city_name;
    private $city_kana_name;
    private $scraping_url;
    private $room_plan;

    public function __construct(Request $request) {
        $this->api_url = 'http://host.docker.internal:8888/api/v1/chintai_app/get_deepl_data/';
        $this->prefecture = PrefecturesService::selectPrefecture($request->prefecture_id);
        $this->scraping_url = $request->scraping_url;

        // 新規追加と更新で処理を分ける
        if ($request->has('city_name') && $request->has('city_kana_name')) {
            // 新規追加の場合
            $this->city_name = $request->city_name;
            $this->city_kana_name = $request->city_kana_name;
        } else {
            // 更新の場合
            $this->city = CitiesService::selectCityByCityId($request->city_id);
        }
        
    }

    // 送られたデータを処理
    public function fetchCsvByRegister(): View
    {
        // カナ名をローマ字に変換する
        $prefecture_roma_name = $this->kanaToRoma($this->prefecture->kana_name);
        $city_roma_name = $this->kanaToRoma($this->city_kana_name);

        // csvファイルを命名する
        $today = date('Ymd') ;
        $csv_file_name = "{$today}_{$prefecture_roma_name}_{$city_roma_name}.csv";

        // apiに送るデータを連想配列に入れる
        $params = [
            'url' => $this->scraping_url,
            'csv_name' => $csv_file_name
        ];
                
        // api呼び出し  
        $response_array = $this->postUrl($params);

        // 文字列を配列に変換
        $plan_list = str_replace("'", '"', $response_array['plan_list']);
        $this->room_plan = json_decode($plan_list, true);

        // 市町村データを保存
        $city = $this->insertCity();

        // 取得データを保存
        $city_csv_file = $this->insertCityCsvFiles($city, $csv_file_name);

        return view('admin/get_info_complete', [
            'city' => $city,
            'city_csv_file' => $city_csv_file
        ]);
    }

    // 送られたデータを処理
    public function fetchCsvByUpdate(): View
    {
        // カナ名をローマ字に変換する
        $prefecture_roma_name = $this->kanaToRoma($this->prefecture->kana_name);
        $city_roma_name = $this->kanaToRoma($this->city->kana_name);

        // csvファイルを命名する
        $today = date('Ymd') ;
        $csv_file_name = "{$today}_{$prefecture_roma_name}_{$city_roma_name}.csv";

        // apiに送るデータを連想配列に入れる
        $params = [
            'url' => $this->scraping_url,
            'csv_name' => $csv_file_name
        ];
                
        // api呼び出し  
        $response_array = $this->postUrl($params);

        // 文字列を配列に変換
        $plan_list = str_replace("'", '"', $response_array['plan_list']);
        $this->room_plan = json_decode($plan_list, true);

        // 取得データを追加
        $city_csv_file = $this->insertCityCsvFiles($this->city, $csv_file_name);

        return view('admin/get_info_complete', [
            'city' => $this->city,
            'city_csv_file' => $city_csv_file
        ]);
    }

    // apiでデータを取得するして連想配列で返す
    public function postUrl(array $params): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
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
        $city = CitiesService::insertCity($this->city_name, $this->city_kana_name, $this->prefecture->id);
        return $city;
    }
    
    // 取得データの保存
    public function insertCityCsvFiles(Model $city, $csv_file_name): Model
    {
        $csv_file = CityCsvFilesService::insertCityCsvFile($csv_file_name, $city, $this->room_plan);
        return $csv_file;
    }

    // ひらがなをローマ字に変換する
    public function kanaToRoma(string $hiragana): string
    {   
        $hiraganaToRomaDict = [
            'あ' => 'a', 'い' => 'i', 'う' => 'u', 'え' => 'e', 'お' => 'o',
            'か' => 'ka', 'き' => 'ki', 'く' => 'ku', 'け' => 'ke', 'こ' => 'ko',
            'さ' => 'sa', 'し' => 'shi', 'す' => 'su', 'せ' => 'se', 'そ' => 'so',
            'た' => 'ta', 'ち' => 'chi', 'つ' => 'tsu', 'て' => 'te', 'と' => 'to',
            'な' => 'na', 'に' => 'ni', 'ぬ' => 'nu', 'ね' => 'ne', 'の' => 'no',
            'は' => 'ha', 'ひ' => 'hi', 'ふ' => 'fu', 'へ' => 'he', 'ほ' => 'ho',
            'ま' => 'ma', 'み' => 'mi', 'む' => 'mu', 'め' => 'me', 'も' => 'mo',
            'や' => 'ya', 'ゆ' => 'yu', 'よ' => 'yo',
            'ら' => 'ra', 'り' => 'ri', 'る' => 'ru', 'れ' => 're', 'ろ' => 'ro',
            'わ' => 'wa', 'を' => 'wo',
            'ん' => 'n',
            'きゃ' => 'kya', 'きゅ' => 'kyu', 'きょ' => 'kyo',
            'しゃ' => 'sha', 'しゅ' => 'shu', 'しょ' => 'sho',
            'ちゃ' => 'cha', 'ちゅ' => 'chu', 'ちょ' => 'cho',
            'にゃ' => 'nya', 'にゅ' => 'nyu', 'にょ' => 'nyo',
            'ひゃ' => 'hya', 'ひゅ' => 'hyu', 'ひょ' => 'hyo',
            'みゃ' => 'mya', 'みゅ' => 'myu', 'みょ' => 'myo',
            'りゃ' => 'rya', 'りゅ' => 'ryu', 'りょ' => 'ryo',
            'が' => 'ga', 'ぎ' => 'gi', 'ぐ' => 'gu', 'げ' => 'ge', 'ご' => 'go',
            'ざ' => 'za', 'じ' => 'ji', 'ず' => 'zu', 'ぜ' => 'ze', 'ぞ' => 'zo',
            'だ' => 'da', 'ぢ' => 'ji', 'づ' => 'zu', 'で' => 'de', 'ど' => 'do',
            'ば' => 'ba', 'び' => 'bi', 'ぶ' => 'bu', 'べ' => 'be', 'ぼ' => 'bo',
            'ぱ' => 'pa', 'ぴ' => 'pi', 'ぷ' => 'pu', 'ぺ' => 'pe', 'ぽ' => 'po',
            'っ' => '',
        ];
    
        $roma = '';
        $i = 0;
        $len = mb_strlen($hiragana);
    
        while ($i < $len) {
            // 2文字のパターンを先に確認
            $doubleChar = mb_substr($hiragana, $i, 2);
            if (isset($hiraganaToRomaDict[$doubleChar])) {
                $roma .= $hiraganaToRomaDict[$doubleChar];
                $i += 2;
            } else {
                // 1文字のパターン
                $char = mb_substr($hiragana, $i, 1);
                $roma .= isset($hiraganaToRomaDict[$char]) ? $hiraganaToRomaDict[$char] : $char;
                $i++;
            }
        }
        return $roma;
    }

}