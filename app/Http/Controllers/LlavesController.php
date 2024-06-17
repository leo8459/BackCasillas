<?php

namespace App\Http\Controllers;

use App\Models\llaves;
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
        return llaves::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $llaves = new llaves();
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
    public function show(llaves $llaves)
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
    public function update(Request $request, llaves $llaves)
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
    public function destroy(llaves $llaves)
    {
        $llaves->delete();
        return $llaves;
    }
}
