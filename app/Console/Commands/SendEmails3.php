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
    protected $description = 'Send emails to notify customers when their rental expires today';

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

            // Enviar el correo usando la vista Blade
            Mail::send('emails.alquiler_vencimiento_hoy', compact('cliente', 'casilla', 'alquilere'), function ($message) use ($cliente, $subject) {
                $message->to($cliente->email);
                $message->subject($subject);
            });

            Log::info("Correo enviado a: " . $cliente->email . " por el alquiler de la casilla: " . $casilla->nombre);
        }
    }
}