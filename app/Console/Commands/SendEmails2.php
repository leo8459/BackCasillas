<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alquilere;
use Carbon\Carbon;
use App\Mail\Confirmationagbcmail;


class SendEmails2 extends Command
{
    protected $signature = 'emails:send2';
    protected $description = 'Send emails to notify customers when their rental expires';

    public function handle()
    {
        $alquileres = Alquilere::where('fin_fecha', '>', Carbon::now())
                                ->where('fin_fecha', '<=', Carbon::now()->addDays(30))
                                ->get();
    
        foreach ($alquileres as $alquilere) {
            $cliente = $alquilere->cliente;
            $casilla = $alquilere->casilla; // Accede a la relación "casilla" desde el modelo Alquiler
    
            // Calcular los días restantes hasta la fecha de vencimiento
            $dias_restantes = Carbon::parse($alquilere->fin_fecha)->diffInDays(Carbon::now());
    
            // Verificar si quedan 30 días o menos para la fecha de fin
            if ($dias_restantes <= 30) {
                $subject = '¡Su alquiler está por vencer!';
                $body = 'Estimado/a ' . $cliente->nombre . ', su alquiler de la casilla número ' . $casilla->nombre . ' está por vencer el día ' . Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') . '. Quedan ' . $dias_restantes . ' días para la fecha de vencimiento. Por favor, apersonarse a la ventanilla 2 para realizar la renovación correspondiente de su casillas, Gracias.';
    
                
                // Envía el correo electrónico
                Mail::raw($body, function ($message) use ($cliente, $subject) {
                    $message->to($cliente->email);
                    $message->subject($subject);
                });
            }
        }
    }
    
    




}
