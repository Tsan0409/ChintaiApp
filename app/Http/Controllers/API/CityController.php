<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CitiesService;
use App\Services\CityCsvFilesService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCitiesByPrefecture(Request $request): Collection
    {
        $city = CitiesService::selectCitiesByPrefecture($request->prefecture_id);
        return $city;
    }
}
