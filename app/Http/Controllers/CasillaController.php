<?php

namespace App\Http\Controllers;

use App\Models\Casilla;
use App\Models\Categoria;
use App\Models\Seccione;
use App\Models\Llave;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Confirmationagbcmail;

class CasillaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Casilla::with(['Categoria', 'Seccione','llaves'])->get();

        // return Casilla::with(['Categoria', 'Seccione'])->where('estado',1)->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $casilla = new Casilla();
         $casilla->nombre = $request->nombre;
         $casilla->categoria_id = $request->categoria_id;
         $casilla->seccione_id = $request->seccione_id;
         $casilla->llaves_id = $request->llaves_id;
         $casilla->observacion = $request->observacion;
         $casilla->departamento  = $request->departamento ;


         $casilla->save();
     
         return $casilla;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Casilla  $casilla
     * @return \Illuminate\Http\Response
     */
    public function show($id)
{
    try {
        return Casilla::with(['Categoria', 'Seccione', 'llaves'])->findOrFail($id);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error al recuperar datos de casilla', 'details' => $e->getMessage()], 500);
    }
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Casilla  $casilla
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Casilla $casilla)
    {
         $casilla->nombre = $request->nombre;
         $casilla->observacion = $request->observacion;
         $casilla->categoria_id = $request->categoria_id;
         $casilla->seccione_id = $request->seccione_id;
         $casilla->estado= $request->estado;
         $casilla->departamento  = $request->departamento ;
         $casilla->llaves_id = $request->llaves_id;


         $casilla->save();
         $cliente = Cliente::find($request->cliente_id);

         if ($cliente && $cliente->email) {
             // Envía un correo electrónico al cliente
             Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente));
         } else {
             // Manejar caso en el que no se encuentra el cliente o no tiene correo electrónico
             // Aquí puedes agregar código para manejar este caso según tus necesidades
         }
         return $casilla;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Casilla  $casilla
     * @return \Illuminate\Http\Response
     */
    public function destroy(Casilla $casilla)
    {
        // $casilla->estado = 0;
        // $casilla->save();
        // return $casilla;
        $casilla->delete();
        return $casilla;
    }
    
    public function obtenercasillas($seccionId) {
        $casillas = Casilla::leftJoin('alquileres', function ($join) {
                $join->on('casillas.id', '=', 'alquileres.casilla_id')
                    ->where('alquileres.estado', '=', 1);
            })
            ->leftJoin('clientes', 'alquileres.cliente_id', '=', 'clientes.id')
            ->leftJoin('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->leftJoin('llaves', 'casillas.llaves_id', '=', 'llaves.id') // Asegurar que se unan las llaves
            ->where('casillas.seccione_id', $seccionId)
            ->select(
                'casillas.id AS casilla_id',
                'casillas.nombre AS casilla_nombre',
                'casillas.observacion AS casilla_observacion',
                'casillas.departamento AS casilla_departamento',
                'categorias.nombre AS categoria_nombre',
                'casillas.seccione_id',
                'casillas.llaves_id',
                'llaves.id AS llave_id', // ID de la llave
                'llaves.nombre AS llave_nombre', // Nombre de la llave
                'llaves.created_at AS llave_creado', // Fecha de creación de la llave
                'llaves.updated_at AS llave_actualizado', // Fecha de actualización de la llave
                'casillas.estado AS casilla_estado',
                'alquileres.id AS alquiler_id',
                'alquileres.nombre AS alquiler_nombre',
                'alquileres.cliente_id',
                'alquileres.estado AS alquiler_estado',
                'clientes.nombre AS cliente_nombre',
                'clientes.carnet'
            )
            ->get();
    
        // Revisar si la consulta devuelve correctamente los datos de la llave
        if ($casillas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron casillas'], 404);
        }
    
        return response()->json(['casillas' => $casillas]);
    }
    
    
    public function busquedas ($busquedaid) {
        $casillas = Casilla::leftJoin('alquileres', function ($join) {
                $join->on('casillas.id', '=', 'alquileres.casilla_id')
                    ->where('alquileres.estado', '=', 1);
            })
            ->leftJoin('clientes', 'alquileres.cliente_id', '=', 'clientes.id')
            ->leftJoin('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            // ->where('casillas.seccione_id', $busqueda)
            ->select(
                'casillas.id AS casilla_id',
                'casillas.nombre AS casilla_nombre',
                'casillas.observacion AS casilla_observacion',
                'casillas.departamento  AS casilla_departamento',
                'categorias.nombre AS categoria_nombre',
                'casillas.seccione_id',
                'casillas.estado AS casilla_estado',
                'alquileres.id AS alquiler_id',
                'alquileres.nombre AS alquiler_nombre',
                'alquileres.cliente_id',
                'alquileres.estado AS alquiler_estado',
                'clientes.nombre AS cliente_nombre',
                'clientes.carnet'
            )
            ->get();
    
        $response = [
            'casillas' => $casillas,
        ];
    
        return $response;
    }
    public function obtenerTodasLasCasillas()
    {
        $casillas = Casilla::leftJoin('alquileres', function ($join) {
                $join->on('casillas.id', '=', 'alquileres.casilla_id')
                    ->where('alquileres.estado', '=', 1);
            })
            ->leftJoin('clientes', 'alquileres.cliente_id', '=', 'clientes.id')
            ->leftJoin('categorias', 'casillas.categoria_id', '=', 'categorias.id')
            ->leftJoin('secciones', 'casillas.seccione_id', '=', 'secciones.id')
            ->leftJoin('llaves', 'casillas.llaves_id', '=', 'llaves.id') // Unimos la tabla de llaves
            ->select(
                'casillas.id AS casilla_id',
                'casillas.nombre AS casilla_nombre',
                'casillas.observacion AS casilla_observacion',
                'casillas.departamento AS casilla_departamento',
                'categorias.nombre AS categoria_nombre',
                'secciones.nombre AS seccion_nombre',
                'casillas.seccione_id',
                'casillas.llaves_id',
                'llaves.nombre AS llave_nombre', // Añadimos el nombre de la llave
                'llaves.id AS llave_id', // Añadimos el ID de la llave
                'casillas.estado AS casilla_estado',
                'alquileres.id AS alquiler_id',
                'alquileres.nombre AS alquiler_nombre',
                'alquileres.cliente_id',
                'alquileres.estado AS alquiler_estado',
                'clientes.nombre AS cliente_nombre',
                'clientes.carnet'
            )
            ->get();
    
        return response()->json([
            'casillas' => $casillas,
        ]);
    }
    

   
    
    
}
