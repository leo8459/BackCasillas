<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\AlquilereController;

class UpdateAlquileresVencidos extends Command
{
    protected $signature = 'alquileres:updateVencidos';
    protected $description = 'Actualizar el estado de los alquileres vencidos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new AlquilereController();
        $controller->updateVencidos();

        $this->info('Estados de alquileres y casillas actualizados correctamente.');
    }
}
