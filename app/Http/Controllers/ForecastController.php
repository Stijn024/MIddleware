<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    private const BASE_URL = 'https://api.open-meteo.com/v1/forecast';
    private const LATITUDE_MAP = [
        'Amsterdam' => 52.3738,
        'Paris' => 48.8567,
    ];
    private const LONGITUDE_MAP = [
        'Amsterdam' => 4.8910,
        'Paris' => 2.3510,
    ];

    public function forecast(string $city)
    {
        $lat = self::LATITUDE_MAP[$city];
        $lon = self::LONGITUDE_MAP[$city];
        $url = self::BASE_URL . "?latitude={$lat}&longitude={$lon}&timezone=Europe/Amsterdam&daily=temperature_2m_max,rain_sum,windspeed_10m_max,winddirection_10m_dominant";

        $data = Http::get($url)->json();

        $response = [];
        foreach ($data['daily']['time'] as $i => $day) {
            $response[$day] = [
                'temperature' => $data['daily']['temperature_2m_max'][$i],
                'rain' => $data['daily']['rain_sum'][$i],
                'windspeed' => $data['daily']['windspeed_10m_max'][$i],
                'winddirection' => $data['daily']['winddirection_10m_dominant'][$i],
            ];
        }

        return $response;
    }
}