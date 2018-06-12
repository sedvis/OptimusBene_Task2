<?php

namespace App\Listeners;

use App\Events\WeatherChangeEvent;
use App\Mail\WindAlert;
use App\Subscription;
use Mail;

class WeatherChangeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object $event
     *
     * @return void
     */
    public function handle(WeatherChangeEvent $event)
    {
        if (isset($event->weather_data->cod) && $event->weather_data->cod == 200) {
            $city      = $event->weather_data->name;
            $speed     = $event->weather_data->wind->speed;
            $direction = $event->weather_data->wind->deg;

            if ($speed >= config('owm.wind_threshold')) {
                $subscriptions = Subscription::where('status', 'CALM')->orWhereNull('status')->get();
                $status        = "WINDY";
            } else {
                $subscriptions = Subscription::where('status', 'WINDY')->orWhereNull('status')->get();
                $status        = "CALM";
            }
            /** @var Subscription $item */
            foreach ($subscriptions as $item) {
                Mail::to($item->email)->send(new WindAlert($speed, $city, $direction));
                $item->status = $status;
                $item->save();
            }
        }
    }
}
