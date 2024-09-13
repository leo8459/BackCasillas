<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alquilere;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEmails extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Send emails to notify customers when their rental expires';

    public function handle()
    {
        $hoy = Carbon::now();
        
        $alquileres = Alquilere::whereBetween('fin_fecha', [
            $hoy->copy()->subDays(7)->toDateString(),
            $hoy->toDateString()
        ])->get();

        if ($alquileres->isEmpty()) {
            Log::info('No hay alquileres vencidos en los últimos 7 días.');
        } else {
            Log::info('Se encontraron ' . $alquileres->count() . ' alquileres vencidos en los últimos 7 días.');
        }

        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla;

            $dias_transcurridos = Carbon::parse($alquilere->fin_fecha)->diffInDays($hoy);

            if ($dias_transcurridos <= 7) {
                $subject = '¡Su alquiler ha vencido!';

                // Enviar el correo usando una vista
                Mail::send('emails.alquiler_vencido', compact('cliente', 'casilla', 'dias_transcurridos'), function ($message) use ($cliente, $subject) {
                    $message->to($cliente->email);
                    $message->subject($subject);
                });

                Log::info('Se envió correo al cliente ' . $cliente->nombre . ' por vencimiento de su alquiler.');
            }
        }
    }
}
