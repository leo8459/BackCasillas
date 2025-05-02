<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservaCasillaNotification extends Notification
{
    use Queueable;

    protected $reserva;
    protected $casilla;

    public function __construct($reserva, $casilla)
    {
        $this->reserva = $reserva;
        $this->casilla = $casilla;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    $whatsappUrl = 'https://wa.me/59175200503'; // Enlace de WhatsApp con el número en formato internacional
    
    return (new MailMessage)
                ->subject('Reserva de Casilla')
                ->greeting('¡Hola ' . $this->reserva->nombre . '!')
                ->line('Has reservado la casilla número ' . $this->casilla->id . '.')
                ->line('Tienes 3 días para pasar por la Agencia Boliviana de Correos (AGBC) y cancelar la reserva o comuniquese con este número para hacer el pago de su reserva.')
                ->action('Contactar por WhatsApp', $whatsappUrl) // Agrega un botón para WhatsApp
                ->line('Dirección de la agencia: Avenida Mariscal Santa Cruz Esquina Calle Oruro Edificio Telecomunicaciones')
                ->line('Gracias por utilizar nuestros servicios.');
}


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
