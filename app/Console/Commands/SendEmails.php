<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Alquilere;
use Carbon\Carbon;
// use App\Mail\Confirmationagbcmail;


class SendEmails extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Send emails to notify customers when their rental expires';

    public function handle()
{
    $alquileres = Alquilere::where('fin_fecha', '<=', Carbon::now())->get();

    foreach ($alquileres as $alquilere) {
        $cliente = $alquilere->cliente;
        $casilla = $alquilere->casilla; // Accede a la relación "casilla" desde el modelo Alquiler

        // Calcular los días transcurridos desde la fecha de vencimiento hasta hoy
        $dias_transcurridos = Carbon::parse($alquilere->fin_fecha)->diffInDays(Carbon::now());

        // Verificar si la diferencia en días es mayor que 15
        if ($dias_transcurridos < 46) {
            $subject = '¡Su alquiler ha vencido!';
            $body = 'Estimado/a ' . $cliente->nombre . ', su alquiler de la casilla número ' . $casilla->nombre . ' ha vencido el día ' . Carbon::parse($alquilere->fin_fecha)->format('d/m/Y') . '. Han pasado ' . $dias_transcurridos . ' días desde entonces. Por favor, apersonarse a la ventanilla 2 para realizar la renovación correspondiente de su casillas, Pasado los 45 dias su casilla pasara a estar disponible para un nuevo usuario, Gracias.';

            // Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente, $subject, $body));

            Mail::raw($body, function ($message) use ($cliente, $subject) {
                $message->to($cliente->email);
                $message->subject($subject);
            });
        }
    }
}


}
