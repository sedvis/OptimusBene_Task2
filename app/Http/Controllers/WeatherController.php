<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Subscription;
use Cache;
use Cmfcmf\OpenWeatherMap;
use Cmfcmf\OpenWeatherMap\CurrentWeather;
use Gmopx\LaravelOWM\LaravelOWM;
use Illuminate\Http\Request;
use Validator;

class WeatherController extends Controller
{
    protected $weather;

    /**
     * WeatherController constructor.
     * @param $weather
     */
    public function __construct(OpenWeatherMap $weather)
    {
        $this->weather = $weather;
        $this->weather->setApiKey(config('laravel-owm.api_key'));

    }


    public function index()
    {
        $current_city = Setting::where('key', 'city')->first()->value ?? 'Kaunas';
        return view('welcome', compact('current_city'));
    }

    /**
     * Returns weather data for selected city
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $city = request()->get('city') ?? 'Kaunas';
        /** @var LaravelOWM $owm */

        if (Cache::has('owm:' . $city)) {
            $data = Cache::get('owm:' . $city);
            $msg = 'from cache';
        } else {
            $raw = $this->weather->getRawWeatherData($city, 'metric', 'en', '', 'json');
            $data = json_decode($raw);
            Cache::put('owm:' . $city, $data, 5);
            $msg = 'live';
        }

        return $this->sendResponse($data, $msg);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), ['city' => 'string|required']);

        if ($validator->fails()) {
            return $this->sendError('City is required');
        }
        $setting = Setting::updateOrCreate(['key' => 'city'], [
            'key'   => 'city',
            'value' => $request->post('city')
        ]);

        return $this->sendResponse($setting, 'City saved successfully');
    }

    public function subscribe(Request $request)
    {

        $validator = Validator::make($request->all(), ['email' => 'email|required']);

        if ($validator->fails()) {
            return $this->sendError('Email is empty or invalid');
        }

        $subscription = Subscription::create(request()->all());

        return $this->sendResponse($subscription, 'City saved successfully');
    }

}
