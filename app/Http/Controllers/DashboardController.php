<?php

namespace App\Http\Controllers;
use App\Models\Alquilere;
use App\Models\Cliente;
use App\Models\Casilla;
use App\Models\Seccione;
use App\Models\User;
use App\Models\Precio;
use App\Models\Categoria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function patito() {
        $today = Carbon::today();

        // Consulta para obtener la suma de multas de casillas pequeñas con estado 1 del día de hoy
        $totalMultasPequenas = Alquilere::join('casillas', 'alquileres.casilla_id', '=', 'casillas.id')
            ->join('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->where('categorias.nombre', 'Pequeña')
            ->where('alquileres.estado', 1)
            ->whereDate('alquileres.created_at', $today)
            ->sum(DB::raw('CAST(alquileres.nombre AS DECIMAL(10, 2))'));

        // Consulta para obtener la suma de multas de casillas medianas con estado 1 del día de hoy
        $totalMultasMedianas = Alquilere::join('casillas', 'alquileres.casilla_id', '=', 'casillas.id')
            ->join('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->where('categorias.nombre', 'Mediana')
            ->where('alquileres.estado', 1)
            ->whereDate('alquileres.created_at', $today)
            ->sum(DB::raw('CAST(alquileres.nombre AS DECIMAL(10, 2))'));

        // Consulta para obtener la suma de multas de gabeta con estado 1 del día de hoy
        $totalMultasGabeta = Alquilere::join('casillas', 'alquileres.casilla_id', '=', 'casillas.id')
            ->join('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->where('categorias.nombre', 'Gabeta')
            ->where('alquileres.estado', 1)
            ->whereDate('alquileres.created_at', $today)
            ->sum(DB::raw('CAST(alquileres.nombre AS DECIMAL(10, 2))'));

        // Consulta para obtener la suma de multas de cajón con estado 1 del día de hoy
        $totalMultasCajon = Alquilere::join('casillas', 'alquileres.casilla_id', '=', 'casillas.id')
            ->join('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->where('categorias.nombre', 'Cajón')
            ->where('alquileres.estado', 1)
            ->whereDate('alquileres.created_at', $today)
            ->sum(DB::raw('CAST(alquileres.nombre AS DECIMAL(10, 2))'));

        // Suma total de todas las multas
        $totalMultas = $totalMultasPequenas + $totalMultasMedianas + $totalMultasGabeta + $totalMultasCajon;

        return [
            "alquileres" => Alquilere::where('estado', 1)->count(),
            "users" => User::where('estado', 1)->count(),
            "clientes" => Cliente::where('estado', 1)->count(),
            "casillas" => Casilla::where('estado', 1)->count(),
            "precios" => Precio::where('estado', 1)->count(),
            "categorias" => Categoria::where('nombre', 'Pequeña')->count(),
            "pequeñas" => Casilla::where('categoria_id', 1)->count(),
            "medianas" => Casilla::where('categoria_id', 2)->count(),
            "gabetas" => Casilla::where('categoria_id', 3)->count(),
            "cajones" => Casilla::where('categoria_id', 4)->count(),
            "pequeñaslibres1" => Casilla::where('categoria_id', 1)->where('estado', 1)->count(),
            "medianaslibres" => Casilla::where('categoria_id', 2)->where('estado', 1)->count(),
            "gabetaslibres" => Casilla::where('categoria_id', 3)->where('estado', 1)->count(),
            "cajoneslibres" => Casilla::where('categoria_id', 4)->where('estado', 1)->count(),
            "pequeñasocupadas" => Alquilere::whereHas('casilla', function ($query) {
                $query->where('categoria_id', 1);
            })->where('estado', 1)->count(),
            "medianasocupadas" => Alquilere::whereHas('casilla', function ($query) {
                $query->where('categoria_id', 2);
            })->where('estado', 1)->count(),
            "gabetaocupadas" => Alquilere::whereHas('casilla', function ($query) {
                $query->where('categoria_id', 3);
            })->where('estado', 1)->count(),
            "cajonocupadas" => Alquilere::whereHas('casilla', function ($query) {
                $query->where('categoria_id', 4);
            })->where('estado', 1)->count(),
            "total_multas_pequenas" => $totalMultasPequenas,
            "total_multas_medianas" => $totalMultasMedianas,
            "total_multas_gabeta" => $totalMultasGabeta,
            "total_multas_cajon" => $totalMultasCajon,
            "total_multas" => $totalMultas,
        ];
    }
}
