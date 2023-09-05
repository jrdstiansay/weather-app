<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlaceController extends Controller
{
    /**
     * Get Places from FourSquare API
     *
     * @return JsonResponse
     */
    public function index(Request $req): JsonResponse
    {
        $data = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => env('FOURSQUARE_API_KEY'),
        ])->get(
                config('constant.foursquareUrl'),
                [
                    'q' => $req->q,
                    'near' => $req->near,
                    'locale' => 'en'
                ]
            );

        return response()->json($data->json());
    }
}