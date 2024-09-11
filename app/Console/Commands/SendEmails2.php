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
    protected $description = 'Send emails to notify customers when their rental is about to expire';

    public function handle()
    {
        $hoy = Carbon::now();
        $fecha_límite = $hoy->copy()->addDays(30);

        $alquileres = Alquilere::where('fin_fecha', '>=', $hoy->copy()->addDays(29)->toDateString())
                                ->where('fin_fecha', '<=', $fecha_límite->toDateString())
                                ->get();

        if ($alquileres->isEmpty()) {
            Log::info('No hay alquileres próximos a vencer en los próximos 30 días.');
        }

        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla;

            // Calcular los días restantes hasta la fecha de vencimiento
            $dias_restantes = Carbon::parse($alquilere->fin_fecha)->diffInDays($hoy);

            // Verificar si quedan entre 29 y 30 días para la fecha de fin
            if ($dias_restantes == 29 || $dias_restantes == 30) {
                $subject = '¡Su alquiler está por vencer!';

                // Enviar el correo usando la vista de Blade
                Mail::send('emails.alquiler_por_vencer', compact('cliente', 'casilla', 'dias_restantes', 'alquilere'), function ($message) use ($cliente, $subject) {
                    $message->to($cliente->email);
                    $message->subject($subject);
                });

                Log::info('Se envió correo al cliente ' . $cliente->nombre . ' notificando que su alquiler está por vencer.');
            }
        }
    }
}