<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alquilere;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEmails3 extends Command
{
    protected $signature = 'emails:send3';
    protected $description = 'Send emails to notify customers when their rental expires';

    public function handle()
    {
        $hoy = Carbon::now()->toDateString();

        $alquileres = Alquilere::where('fin_fecha', '=', $hoy)->get();

        if ($alquileres->isEmpty()) {
            Log::info("No se encontraron alquileres que vencen hoy.");
        } else {
            Log::info("Se encontraron " . $alquileres->count() . " alquileres que vencen hoy.");
        }

        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla;

            $subject = '¡Su alquiler vence hoy!';
            $body = 'Estimado/a ' . $cliente->nombre . ', su alquiler de la casilla número ' . $casilla->nombre . ' vence hoy, ' . Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') . '. Por favor, apersonarse a la ventanilla 32 para realizar la renovación correspondiente de su casilla. Gracias.';

            // Envía el correo electrónico
            Mail::raw($body, function ($message) use ($cliente, $subject) {
                $message->to($cliente->email);
                $message->subject($subject);
            });

            Log::info("Correo enviado a: " . $cliente->email . " por el alquiler de la casilla: " . $casilla->nombre);
        }
    }
}
