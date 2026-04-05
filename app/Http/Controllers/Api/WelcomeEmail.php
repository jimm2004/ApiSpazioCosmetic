<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $role;

    public function __construct($name, $role = 'cliente')
    {
        $this->name = $name;
        $this->role = $role;
    }

    public function build()
    {
        return $this->subject('Bienvenido a SpazioStore')
                    ->view('emails.welcome');
    }
}