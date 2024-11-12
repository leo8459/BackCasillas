<?php

namespace App\Http\Controllers;

use App\Models\Llave; // Asegúrate de importar correctamente el modelo
use Illuminate\Http\Request;

class LlavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Llave::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $llaves = new Llave();
        $llaves->nombre = $request->nombre;

        $llaves->save();
    
        return $llaves;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\llaves  $llaves
     * @return \Illuminate\Http\Response
     */
    public function show(Llave $llaves)
    {
        return $llaves;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\llaves  $llaves
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Llave $llaves)
    {
        $llaves->nombre = $request->nombre;


        $llaves->save();
    
        return $llaves;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\llaves  $llaves
     * @return \Illuminate\Http\Response
     */
    public function destroy(Llave $llaves)
    {
        $llaves->delete();
        return $llaves;
    }
}
