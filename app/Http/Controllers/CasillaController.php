<?php

namespace App\Http\Controllers;

use App\Models\Casilla;
use App\Models\Categoria;
use App\Models\Seccione;
use App\Models\llaves;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Confirmationagbcmail;
use Illuminate\Support\Facades\File;

class CasillaController extends Controller
{


    public function backupLaravel()
    {
        // Elimina el límite de tiempo de ejecución
    set_time_limit(0);

    
        $zip = new \ZipArchive();
        $backupFile = storage_path('app/backups/laravel_backup_' . date('Y-m-d_H-i-s') . '.zip');
    
        if ($zip->open($backupFile, \ZipArchive::CREATE) === TRUE) {
            $this->addFolderToZip(base_path(), $zip);
            $zip->close();
            return response()->download($backupFile);
        } else {
            return response()->json(['error' => 'No se pudo generar el backup'], 500);
        }
    }
    
    private function addFolderToZip($folder, &$zip, $parentFolder = '')
    {
        $files = scandir($folder);
    
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
    
            $filePath = $folder . '/' . $file;
            $relativePath = $parentFolder . $file;
    
            if (is_dir($filePath)) {
                $zip->addEmptyDir($relativePath);
                $this->addFolderToZip($filePath, $zip, $relativePath . '/');
            } else {
                $zip->addFile($filePath, $relativePath);
            }
        }
    }
    

    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Casilla::with(['Categoria', 'Seccione'])->get();

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
    public function show(Casilla $casilla)
    {
        $casilla->categoria = $casilla->Categoria;
        $casilla->seccione = $casilla->Seccione;

        return $casilla;

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
            ->where('casillas.seccione_id', $seccionId)
            ->select(
                'casillas.id AS casilla_id',
                'casillas.nombre AS casilla_nombre',
                'casillas.observacion AS casilla_observacion',
                'casillas.departamento  AS casilla_departamento',
                'categorias.nombre AS categoria_nombre',
                'casillas.seccione_id',
                'casillas.llaves_id',
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
        ->select(
            'casillas.id AS casilla_id',
            'casillas.nombre AS casilla_nombre',
            'casillas.observacion AS casilla_observacion',
            'casillas.departamento  AS casilla_departamento',
            'categorias.nombre AS categoria_nombre',
            'secciones.nombre AS seccion_nombre',
            'casillas.seccione_id',
            'casillas.llaves_id',
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
