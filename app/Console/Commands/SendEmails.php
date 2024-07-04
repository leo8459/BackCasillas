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
        $fecha_hace_7_dias = $hoy->copy()->subDays(7);


        $alquileres = Alquilere::where('fin_fecha', '=', $fecha_hace_7_dias->toDateString())->get();

        if ($alquileres->isEmpty()) {
        } else {
        }

        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla;

            // Calcular los días transcurridos desde la fecha de vencimiento hasta hoy
            $dias_transcurridos = Carbon::parse($alquilere->fin_fecha)->diffInDays($hoy);

            // Verificar si la diferencia en días es exactamente 7
            if ($dias_transcurridos == 7) {
                $subject = '¡Su alquiler ha vencido!';
                $body = 'Estimado/a ' . $cliente->nombre . ', su alquiler de la casilla número ' . $casilla->nombre . ' ha vencido el día ' . Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') . '. Han pasado ' . $dias_transcurridos . ' días desde entonces. Por favor, apersonarse a la ventanilla 32 para realizar la renovación correspondiente de su casilla. Pasado los días ya mencionados, pasará a estar disponible para un nuevo usuario. Gracias.';

                // Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente, $subject, $body));

                Mail::raw($body, function ($message) use ($cliente, $subject) {
                    $message->to($cliente->email);
                    $message->subject($subject);
                });
            } else {
            }
        }
    }
}
