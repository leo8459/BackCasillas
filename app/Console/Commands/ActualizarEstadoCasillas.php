<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ActualizarEstadoCasillas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'casillas:actualizar-estado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza el estado de las casillas a 0 si el alquiler ha terminado y están en estado 4';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement("
            UPDATE casillas c
            JOIN alquileres a ON c.id = a.casilla_id
            SET c.estado = 0
            WHERE a.fin_fecha > CURDATE()
              AND c.estado = 4
        ");

        $this->info('Estado de casillas actualizado correctamente.');
        return 0;
    }
}
