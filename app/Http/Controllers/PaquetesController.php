<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use Illuminate\Http\Request;
use App\Models\Alquilere;

class PaquetesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Paquete::with(['Alquilere'])->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $paquetes = new Paquete();
        $paquetes->codigo = $request->codigo;
        $paquetes->fecha = $request->fecha;
        $paquetes->alquilere_id = $request->alquilere_id;
        $paquetes->departamento  = $request->departamento;


        $paquetes->save();
    
        return $paquetes;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paquete  $paquetes
     * @return \Illuminate\Http\Response
     */
    public function show(Paquete $paquetes)
    {
        $paquetes->alquilere = $paquetes->alquilere;

        return $paquetes;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paquete  $paquetes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paquete $paquetes)
    {
        $paquetes->codigo = $request->codigo;
        $paquetes->fecha = $request->fecha;
        $paquetes->alquilere_id = $request->alquilere_id;
        $paquetes->departamento  = $request->departamento ;


        $paquetes->save();
    
        return $paquetes;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paquete  $paquetes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paquete $paquetes)
    {
        $paquetes->delete();
        return $paquetes;
    }
}
