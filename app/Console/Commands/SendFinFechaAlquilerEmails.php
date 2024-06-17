<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alquilere;
use App\Mail\FinFechaAlquilerMail;
use Illuminate\Support\Facades\Mail;

class SendFinFechaAlquilerEmails extends Command
{
    protected $signature = 'send:finfechaalquileremails';

    protected $description = 'Envía correos electrónicos a los clientes cuyos alquileres han finalizado';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener los alquileres que han alcanzado su fecha de fin
        $alquileres = Alquilere::whereDate('fin_fecha', now())->get();

        // Envía un correo electrónico para cada alquiler
        foreach ($alquileres as $alquiler) {
            // Obtener el cliente asociado al alquiler
            $cliente = $alquiler->cliente;

            // Verificar si el cliente existe
            if ($cliente) {
                // Envía un correo electrónico al cliente
                Mail::to($cliente->email)->send(new FinFechaAlquilerMail($alquiler));
            }
        }

        $this->info('Los correos electrónicos de fin de alquiler han sido enviados correctamente.');
    }
}
