<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $password;

    public function __construct($name, $email, $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->to($this->email)
            ->subject('Добро пожаловать в наш магазин!')
            ->view('emails.welcome')
            ->with([
                'name' => $this->name,
                'password' => $this->password,
            ]);
    }
}
