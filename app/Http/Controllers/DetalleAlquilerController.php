<?php

namespace App\Http\Controllers;

use App\Models\detalle_alquiler;
use App\Models\Alquilere; // Asegúrate de importar el modelo Alquilere
use Illuminate\Http\Request;

class DetalleAlquilerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\detalle_alquiler  $detalle_alquiler
     * @return \Illuminate\Http\Response
     */
    public function show(detalle_alquiler $detalle_alquiler)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\detalle_alquiler  $detalle_alquiler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, detalle_alquiler $detalle_alquiler)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\detalle_alquiler  $detalle_alquiler
     * @return \Illuminate\Http\Response
     */
    public function destroy(detalle_alquiler $detalle_alquiler)
    {
        //
    }
}
