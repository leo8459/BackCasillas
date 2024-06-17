<?php

namespace App\Http\Controllers;

use App\Models\Seccione;
use Illuminate\Http\Request;

class SeccioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Seccione::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $seccione = new Seccione();
        $seccione->nombre = $request->nombre;

        $seccione->save();
    
        return $seccione;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Seccione  $seccione
     * @return \Illuminate\Http\Response
     */
    public function show(Seccione $seccione)
    {
        return $seccione;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seccione  $seccione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seccione $seccione)
    {
        $seccione->nombre = $request->nombre;
        $seccione->estado= $request->estado;


        $seccione->save();
    
        return $seccione;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seccione  $seccione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seccione $seccione)
    {
        $seccione->estado = 0;
        $seccione->save();
        return $seccione;
    }
}
