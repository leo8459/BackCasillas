<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;



class Confirmationagbcmail extends Mailable
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
    return $this->view('emails.registro-exitoso')
        ->with(['cliente' => $this->cliente]) // Cambia '->' a '=>'
        ->subject('Confirm Your Email Address');
}

}

