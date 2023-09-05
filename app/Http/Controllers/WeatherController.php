<?php

namespace App\Http\Controllers;

use App\Http\Requests\WeatherRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    /**
     * Get Weather forecast for 5 days
     *
     * @return JsonResponse
     */
    public function index(WeatherRequest $req): JsonResponse
    {
        $params = $req->safe();
        $data = Http::get(config('constant.openWeatherUrl'), [
            ...$params,
            'appId' => env('OPENWEATHER_API_KEY'),
            'units' => config('constant.units'),
        ]);

        return response()->json($data->json());
    }
}