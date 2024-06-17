<?php

namespace App\Http\Controllers;

use App\Models\Precio;
use Illuminate\Http\Request;
use App\Models\Categoria;

class PrecioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Precio::with(['Categoria'])->get();

        // return Precio::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $precio = new Precio();
        $precio->tiempo = $request->tiempo;
         $precio->precio = $request->precio;
         $precio->categoria_id = $request->categoria_id;


         $precio->save();
     
         return $precio;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function show(Precio $precio)
    {
        $precio->categoria = $precio->categoria;


        return $precio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Precio $precio)
    {

        $precio->tiempo = $request->tiempo;
        $precio->precio = $request->precio;
        $precio->categoria_id = $request->categoria_id;
        $precio->estado= $request->estado;

        $precio->save();
     
         return $precio;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Precio  $precio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Precio $precio)
    {
        $precio->estado = 0;
        $precio->save();
        return $precio;
    }
}
