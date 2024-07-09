<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reserva;
use App\Models\Casilla;
use Carbon\Carbon;

class UpdateReservaEstado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservas:update-estado';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar el estado de las reservas después de 3 días si las casillas siguen en estado reservado y eliminar las reservas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Obtener las reservas que fueron creadas hace más de 3 días
        $reservas = Reserva::where('created_at', '<', Carbon::now()->subDays(3))
                           ->get();

        foreach ($reservas as $reserva) {
            // Obtener la casilla asociada
            $casilla = Casilla::find($reserva->casilla_id);
            if ($casilla && $casilla->estado == 5) {
                // Actualizar el estado de la casilla a libre (asumimos que 1 es libre)
                $casilla->estado = 1;
                $casilla->save();

                // Eliminar la reserva
                $reserva->delete();
            }
        }

        $this->info('Estados de reservas actualizados y reservas eliminadas correctamente.');
        return 0;
    }
}
