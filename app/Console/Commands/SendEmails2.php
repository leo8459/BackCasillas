<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alquilere;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendEmails2 extends Command
{
    protected $signature = 'emails:send2';
    protected $description = 'Send emails to notify customers when their rental expires';

    public function handle()
    {
        $hoy = Carbon::now();
        $fecha_límite = $hoy->copy()->addDays(30);


        $alquileres = Alquilere::where('fin_fecha', '>=', $hoy->copy()->addDays(29)->toDateString())
                                ->where('fin_fecha', '<=', $fecha_límite->toDateString())
                                ->get();

        if ($alquileres->isEmpty()) {
        }

        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla;

            // Calcular los días restantes hasta la fecha de vencimiento
            $dias_restantes = Carbon::parse($alquilere->fin_fecha)->diffInDays($hoy);

            // Verificar si quedan entre 29 y 30 días para la fecha de fin
            if ($dias_restantes == 29 || $dias_restantes == 30) {
                $subject = '¡Su alquiler está por vencer!';
                $body = 'Estimado/a ' . $cliente->nombre . ', su alquiler de la casilla número ' . $casilla->nombre . ' está por vencer el día ' . Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') . '. Quedan ' . $dias_restantes . ' días para la fecha de vencimiento. Por favor, apersonarse a la ventanilla 32 para realizar la renovación correspondiente de su casilla. Gracias.';

                // Envía el correo electrónico
                Mail::raw($body, function ($message) use ($cliente, $subject) {
                    $message->to($cliente->email);
                    $message->subject($subject);
                });
            } else {
            }
        }
    }
}
