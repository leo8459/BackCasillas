<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alquilere;
use App\Models\Casilla;
use Illuminate\Support\Facades\Log;

class UpdateAlquileresToOcupadas extends Command
{
    protected $signature = 'alquileres:updateAllToOcupadas';
    protected $description = 'Update all alquileres with "Con Correspondencia" state to "Ocupadas" state';

    public function handle()
    {
        try {
            // Obtener todas las casillas en estado "Con Correspondencia" (estado 2)
            $alquileres = Alquilere::whereHas('casilla', function($query) {
                $query->where('estado', 2);
            })->get();

            $errors = [];

            foreach ($alquileres as $alquilere) {
                try {
                    // Actualizar el estado de la casilla asociada
                    $casilla = Casilla::find($alquilere->casilla_id);
                    if ($casilla) {
                        $casilla->estado = 0; // Cambiar estado a "Ocupado"
                        $casilla->save();
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error actualizando alquiler ID: " . $alquilere->id . " - " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                Log::error(['status' => 'error', 'message' => $errors]);
            }

            Log::info(['status' => 'success', 'message' => 'Todas las casillas "Con Correspondencia" han sido actualizadas a "Ocupado"']);
        } catch (\Exception $e) {
            Log::error(['status' => 'error', 'message' => 'Error en el proceso de actualización a "Ocupadas"', 'exception' => $e->getMessage()]);
        }
    }
}
