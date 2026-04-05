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
        // Usamos with() para asegurar que las variables lleguen a la vista HTML
        return $this->subject('¡Bienvenido a SpazioStore!')
                    ->view('emails.welcome')
                    ->with([
                        'name' => $this->name,
                        'role' => $this->role,
                    ]);
    }
}