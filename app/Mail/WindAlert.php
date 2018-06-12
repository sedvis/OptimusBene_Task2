<?php

namespace App\Mail;

use App\Setting;
use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WindAlert extends Mailable
{
    use Queueable, SerializesModels;

    protected $email;
    protected $speed;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $speed, $city)
    {
        $this->email = $email;
        $this->speed = $speed;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Wind speed has changed');
        $this->text("Alert. Wind");
    }
}
