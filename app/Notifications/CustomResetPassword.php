<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Generamos la URL para restablecer la contraseña (ajusta si usas un frontend separado)
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Le decimos que use nuestra propia vista de Blade
        return (new MailMessage)
            ->subject('Restablecer Contraseña - Spazio Cosmetics')
            ->view('emails.reset-password', ['url' => $url]);
    }
}