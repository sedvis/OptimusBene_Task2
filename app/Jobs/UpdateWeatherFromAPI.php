<?php

namespace App\Jobs;

use App\Events\WeatherChangeEvent;
use App\Setting;
use Cache;
use Cmfcmf\OpenWeatherMap;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateWeatherFromAPI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $weather;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OpenWeatherMap $weather)
    {
        $this->weather = $weather;
        $this->weather->setApiKey(config('laravel-owm.api_key'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $city = Setting::where('key', '=', 'city')->first()->value ?? 'Kaunas';
        if (Cache::has('owm:' . $city)) {
            $data = Cache::get('owm:' . $city);
        } else {
            $raw  = $this->weather->getRawWeatherData($city, 'metric', 'en', '', 'json');
            $data = json_decode($raw);
            Cache::put('owm:' . $city, $data, 5);
        }
        event(new WeatherChangeEvent($data));
    }
}
