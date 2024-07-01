<?php

namespace App\Http\Controllers;

use App\Models\Alquilere;
use App\Models\Casilla;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Precio;
use App\Models\Cajero;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\Confirmationagbcmail;
use Illuminate\Http\Request;
use Carbon\Carbon;


class AlquilereController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Alquilere::with(['Casilla', 'Cliente','Categoria','Precio', 'Cajero'])->get();
        
        // return Alquilere::with(['Casilla', 'Cliente'])->where('estado',1)->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $alquilere = new Alquilere();
         $alquilere->nombre = $request->nombre;
         $alquilere->apertura = $request->apertura;
         $alquilere->habilitacion = $request->habilitacion;
         $alquilere->precio_id = $request->precio_id;
         $alquilere->cliente_id = $request->cliente_id;
         $alquilere->casilla_id = $request->casilla_id;
         $alquilere->categoria_id = $request->categoria_id;
         $alquilere->cajero_id = $request->cajero_id; // Asegúrate de que cajero_id esté llegando correctamente en la solicitud
         $alquilere->estado_pago = $request->estado_pago;
         $alquilere->ini_fecha = $request->ini_fecha;
         $alquilere->fin_fecha = $request->fin_fecha;
         $casilla = Casilla::find($request->casilla_id);

         
         if ($casilla) {
             $casilla->estado = 0;
             $casilla->save();
         }


         $alquilere->save();
        //  $cliente = Cliente::find($request->cliente_id);

        //  if ($cliente) {
        //     // Envía un correo electrónico al cliente
        //     Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente));
        // } else {
        //     // Manejar caso en el que no se encuentra el cliente
        //     // Puedes agregar el código para manejar este caso según tus necesidades
        // }
         return $alquilere;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alquilere  $alquilere
     * @return \Illuminate\Http\Response
     */
    public function show(Alquilere $alquilere)
    {
        $alquilere->cliente = $alquilere->cliente;
         $alquilere->casilla = $alquilere->casilla;
         $alquilere->categoria = $alquilere->categoria;
         $alquilere->precio = $alquilere->precio;
         $alquilere->estado_pago = $alquilere->estado_pago;
         $alquilere->apertura = $request->apertura;
         $alquilere->habilitacion = $request->habilitacion;

        $alquilere->url_pdf = url('web/reportes/alquileres/', $alquilere->id);
        return $alquilere;
    }
    public function pdf(Alquilere $alquilere)
{
    $alquilere = $this->show($alquilere);
    $alquilere->url_pdf = url('api/reportes/alquileres/', $alquilere->id);

    $pdf = PDF::loadView('reports.alquilere', ["alquilere" => $alquilere]);
    return $pdf->stream();
}


    /**
     * Update the specified resource instorage .
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alquilere  $alquilere
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alquilere $alquilere)
{
    // Verificar si la fecha final ha cambiado
    if ($request->fin_fecha != $alquilere->fin_fecha) {
        // Actualizar los datos del alquiler
        $alquilere->nombre = $request->nombre;
        $alquilere->cliente_id = $request->cliente_id;
        $alquilere->casilla_id = $request->casilla_id;
        $alquilere->categoria_id = $request->categoria_id;
        $alquilere->precio_id = $request->precio_id;
        $alquilere->ini_fecha = $request->ini_fecha;
        $alquilere->fin_fecha = $request->fin_fecha;
        $alquilere->estado_pago = $request->estado_pago;
        $alquilere->cajero_id = $request->cajero_id;

        // Cambiar el estado del alquiler
        $alquilere->estado = 0;

        // Guardar los cambios en el alquiler
        $alquilere->save();

        // Crear un nuevo alquiler con los mismos datos del alquiler original
        $nuevoAlquiler = new Alquilere();
        $nuevoAlquiler->nombre = $alquilere->nombre;
        $nuevoAlquiler->cliente_id = $alquilere->cliente_id;
        $nuevoAlquiler->casilla_id = $alquilere->casilla_id;
        $nuevoAlquiler->categoria_id = $alquilere->categoria_id;
        $nuevoAlquiler->precio_id = $alquilere->precio_id;
        $nuevoAlquiler->ini_fecha = $alquilere->ini_fecha;
        $nuevoAlquiler->fin_fecha = $alquilere->fin_fecha;
        $nuevoAlquiler->estado_pago = $alquilere->estado_pago;
        $nuevoAlquiler->cajero_id = $alquilere->cajero_id;

        // Guardar el nuevo alquiler
        $nuevoAlquiler->save();

        return $alquilere;
    }

    // Si la fecha final no ha cambiado, actualizar el alquiler existente con todos los datos del request
    $alquilere->nombre = $request->nombre;
    $alquilere->cliente_id = $request->cliente_id;
    $alquilere->casilla_id = $request->casilla_id;
    $alquilere->categoria_id = $request->categoria_id;
    $alquilere->precio_id = $request->precio_id;
    $alquilere->ini_fecha = $request->ini_fecha;
    $alquilere->fin_fecha = $request->fin_fecha;
    $alquilere->estado_pago = $request->estado_pago;
    $alquilere->cajero_id = $request->cajero_id;

    // Guardar los cambios en el alquiler
    $alquilere->save();
    $cliente = Cliente::find($request->cliente_id);

    if ($cliente) {
       // Envía un correo electrónico al cliente
       
       Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente));
   } else {
       // Manejar caso en el que no se encuentra el cliente
       // Puedes agregar el código para manejar este caso según tus necesidades
   }
    return $alquilere;
}

    

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alquilere  $alquilere
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alquilere $alquilere)
    {

        $casilla = $alquilere->casilla; // Obtener la casilla asociada al alquiler
    if ($casilla) {
        $casilla->estado = 1; // Cambiar el estado de la casilla a 1
        $casilla->save();
    }
        $alquilere->estado = 0;
        $alquilere->save();
        return $alquilere;
    }

    public function updateVencidos()
    {
        $today = Carbon::today();
        $vencidos = Alquilere::where('fin_fecha', '<', $today)->get();

        foreach ($vencidos as $alquilere) {
            // $alquilere->estado = 1; // Cambia el estado del alquiler a 'vencido'
            // $alquilere->save();

            // Actualizar el estado de la casilla asociada
            $casilla = Casilla::find($alquilere->casilla_id);
            if ($casilla) {
                $casilla->estado = 4; // Por ejemplo, cambiar el estado de la casilla a 'disponible'
                $casilla->save();
            }
        }

        return response()->json(['message' => 'Estados de alquileres y casillas actualizados correctamente']);
    }

   
}
