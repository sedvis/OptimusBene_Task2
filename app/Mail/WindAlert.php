<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WindAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $speed;
    protected $city;
    protected $direction;

    /**
     * Create a new message instance.
     * @param $speed
     * @param $city
     * @param $direction
     * @return void
     */
    public function __construct($speed, $city, $direction)
    {
        $this->speed     = $speed;
        $this->city      = $city;
        $this->direction = $direction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Wind speed has changed');
        $this->html("Alert. Wind speed in {$this->city} is {$this->speed}m/s and it is blowing in {$this->direction} degree direction");
    }
}
