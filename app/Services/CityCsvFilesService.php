<?php
namespace App\Services;

use App\Repositories\CityCsvFilesRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CityCsvFilesService
{   
    // 全てのcsvファイルを取得する
    public static function selectAllCityCsvFiles(): Collection
    {
        $all_csv_files = CityCsvFilesRepository::selectAllCityCsvFiles();
        return $all_csv_files;
    }

    // 保存する
    public static function insertCityCsvFile(string $csv_file, Model $city, array $room_plan): Model
    {

        $all_room_plan = ['K', 'LDK', 'R', 'SDK', 'SK', 'SLDK', 'DK'];
        $params = [
            'file_name' => $csv_file,
            'city_id' => $city->id,
        ];

        // 間取りが存在している場合は1、存在しない場合0
        foreach($all_room_plan as $i){
            if (in_array($i, $room_plan)) {
                $params["{$i}"] = 1;
            } else {
                $params["{$i}"] = 0;
            }
        }

        $city_csv_file = CityCsvFilesRepository::insertCityCsvFile($params);
        return $city_csv_file;

    }

    // 市町村番号から最新のデータを取得する
    public static function selectNewCsvFileName(int $city_id): Model
    {
        $csv_file = CityCsvFilesRepository::selectNewCsvFileName($city_id);
        return $csv_file;
    }

    // 市町村番号から最新の間取りを取得
    public static function selectRoomPlans(int $city_id): array
    {
        $city_csv_files = CityCsvFilesRepository::selectNewCsvFileName($city_id);
        $room_plans = [
            'K' => $city_csv_files->K,
            'LDK' => $city_csv_files->LDK,
            'R' => $city_csv_files->R,
            'SDK' => $city_csv_files->SDK,
            'SK' => $city_csv_files->SK,
            'SLDK' => $city_csv_files->SLDK,
            'DK' => $city_csv_files->DK
        ];

        return $room_plans;
    }

}