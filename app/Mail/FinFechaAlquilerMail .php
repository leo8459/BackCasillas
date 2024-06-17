<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class FinFechaAlquilerMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
        Log::info($cliente);

    }

    public function build()
    {
        return $this->view('emails.fin-fecha-alquiler')
        ->with(['cliente' => $this->cliente]) // Cambia '->' a '=>'
        ->subject('Confirm Your Email Address');
    }
}
