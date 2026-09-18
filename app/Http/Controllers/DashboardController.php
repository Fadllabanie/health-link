<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', ['weather' => $this->weather()]);
    }

    /** @return array{temp: float, description: string, icon: string, city: string}|null */
    private function weather(): ?array
    {
        $apiKey = config('services.openweathermap.key');
        $city = config('services.openweathermap.city');

        if (! $apiKey) {
            return null;
        }

        return Cache::remember("weather:{$city}", now()->addMinutes(30), function () use ($apiKey, $city) {
            $response = Http::get('https://api.openweathermap.org/data/2.5/weather', [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'ar',
            ]);

            if (! $response->successful()) {
                return null;
            }

            return [
                'temp' => round($response->json('main.temp')),
                'description' => $response->json('weather.0.description'),
                'icon' => $response->json('weather.0.icon'),
                'city' => $response->json('name'),
            ];
        });
    }
}
