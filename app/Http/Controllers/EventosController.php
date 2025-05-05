<?php

namespace App\Http\Controllers;

use App\Models\Eventos;
use Illuminate\Http\Request;
use App\Models\Alquilere;
use App\Models\Paquete;
use App\Models\Casilla;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Precio;
use App\Models\Cajero;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\Confirmationagbcmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventos = Eventos::with(['cajero'])->get();
        return response()->json($eventos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all()); // Verifica que los datos lleguen correctamente
        $evento = new Eventos();
          $evento->accion = $request->accion;
          $evento->descripcion = $request->descripcion;
          $evento->casilla = $request->codigo;
          $evento->fecha_hora = $request->fecha_hora;

      
          $evento->save();
      
          return $evento;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function show(Eventos $eventos)
    {
        return $eventos;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Eventos $eventos)
    {
         // Crea una nueva instancia de usuario
         $eventos->accion = $request->accion;
         $eventos->descripcion = $request->descripcion;
         $eventos->casilla = $request->codigo;
         $eventos->fecha_hora = $request->fecha_hora;

     
         $eventos->save();
     
         return $eventos;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eventos  $eventos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Eventos $eventos)
    {
        $eventos->estado = 0;
        $eventos->save();
        return $eventos;
    }
}
