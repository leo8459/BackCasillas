<?php

namespace App\Http\Controllers;

use App\Models\Cajero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajeroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Cajero::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Crea una nueva instancia de usuario
        $cajero = new Cajero();
        $cajero->nombre = $request->nombre;
        $cajero->carnet = $request->carnet;
        
        $cajero->email = $request->email;

        $cajero->password = Hash::make($request->input('password'));
    
        $cajero->save();
    
        return $cajero;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cajero  $cajero
     * @return \Illuminate\Http\Response
     */
    public function show(Cajero $cajero)
    {
        return $cajero;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cajero  $cajero
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cajero $cajero)
    {
        // Crea una nueva instancia de usuario
        $cajero->nombre = $request->nombre;
        $cajero->carnet = $request->carnet;
        $cajero->estado= $request->estado;

        $cajero->email = $request->email;

        if(isset($request->password)){
            if(!empty($request->password)){
                $cajero->password = Hash::make($request->password);

            }
        }
        $cajero->save();
    
        return $cajero;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cajero  $cajero
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cajero $cajero)
    {
        $cajero->estado = 0;
        $cajero->save();
        return $cajero;
    }
    public function login3(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        // Intenta autenticar al maestro
        if (Auth::guard('cajero')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Autenticación exitosa, recupera la información del maestro
            $cajero = Auth::guard('cajero')->user();
            
            // Ahora puedes acceder a la información del maestro, por ejemplo, $maestro->nombre, $maestro->email, etc.
    
            // Devuelve un mensaje de éxito junto con los datos del maestro
            return response()->json(['message' => 'Inicio de sesión correcto', 'cajero' => $cajero]);
        }
    
        // Si la autenticación falla, devuelve un mensaje de error
        return response()->json(['error' => 'Credenciales incorrectas'],401);
}
}
