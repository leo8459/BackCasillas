<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Models\Casilla;
use App\Models\Cliente;
use App\Notifications\ReservaCasillaNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log; // Asegúrate de importar la clase Log

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Reserva::with(['Casilla'])->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        $reserva = new Reserva();
        $reserva->nombre = $request->nombre;
        $reserva->carnet = $request->carnet;
        $reserva->telefono = $request->telefono;
        $reserva->email = $request->email;
        $reserva->direccion = $request->direccion;
        $reserva->casilla_id = $request->casilla_id;

        // Establecer el estado de la casilla a 5
        $casilla = Casilla::find($request->casilla_id);
        if ($casilla) {
            $casilla->estado = 5;
            $casilla->save(); // Guarda los cambios en la casilla
        }

        $reserva->save();  // Guarda la reserva en la base de datos

        // Enviar notificación por correo electrónico
        Notification::route('mail', $reserva->email)->notify(new ReservaCasillaNotification($reserva, $casilla));

        return response()->json(['message' => 'Reserva creada y correo enviado'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reserva $reserva)
    {
        $reserva->nombre = $request->nombre;
        $reserva->carnet = $request->carnet;
        $reserva->telefono = $request->telefono;
        $reserva->email = $request->email;
        $reserva->direccion = $request->direccion;
        $reserva->casilla_id = $request->casilla_id;
        $reserva->save();  // Guarda la reserva en la base de datos

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reserva  $reserva
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return $reserva;
    }
}
