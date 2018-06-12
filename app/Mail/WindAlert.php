<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WindAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $speed;
    protected $city;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $speed, $city)
    {
        $this->email = $email;
        $this->speed = $speed;
        $this->city = $city;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Wind speed has changed');
        if ($this->speed >= 10) {
            $this->text("Alert. Wind speed in {$this->city} is above 10");
        } else {
            $this->text("Alert. Wind speed in {$this->city} is below 10");
        }
    }
}
