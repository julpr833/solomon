<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        $name = $notifiable->NombreUsuario ?? 'usuario';

        return (new MailMessage)
            ->subject('Restablecer tu contraseña')
            ->greeting('¡Hola '.$name.'!')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer contraseña', $this->resetUrl($notifiable))
            ->line('Este enlace de restablecimiento expirará en '.$expire.' minutos.')
            ->line('Si no solicitaste este cambio, podés ignorar este correo. Tu contraseña no cambiará.');
    }
}