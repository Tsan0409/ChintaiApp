<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CityCsvFilesService;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class CityCsvFileController extends Controller
{
    // 最新のファイルを取得する
    public function getCityCsvFile(Request $request):Model
    {
        $city_csv_file = CityCsvFilesService::selectNewCsvFileName($request->city_id);
        return $city_csv_file;
    }

    // 最新の部屋タイプを取得する
    public function getRoomPlans(Request $request): array
    {
    $room_plans = CityCsvFilesService::selectRoomPlans($request->city_id);
    return $room_plans;
    }

}
