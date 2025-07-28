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
use Illuminate\Support\Facades\File;

class CasillaController extends Controller
{


    public function backupLaravel()
    {
        // Para evitar que el proceso muera si tarda
        set_time_limit(0);
    
        // 1) Generar el dump de la base de datos
        //    Obteniendo credenciales de .env (usa config('database.connections.mysql.*') si prefieres)
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPass = env('DB_PASSWORD', '');
        $dbName = env('DB_DATABASE', 'casillasagbc20231');
    
        // Ruta donde guardaremos temporalmente el dump
        $dumpPath = storage_path('app/backups/db_backup_' . date('Y-m-d_H-i-s') . '.sql');
    
        // Importante: en Windows puede que debas ajustar la ruta a "mysqldump.exe"
        // y en Linux usar "mysqldump" a secas. Ejemplo:
        // $mysqldumpBinary = 'C:\xampp\mysql\bin\mysqldump'; // Windows
        // $mysqldumpBinary = '/usr/bin/mysqldump'; // Linux
        // O, si está en PATH, con "mysqldump" basta:
        $mysqldumpBinary = 'C:\\XAMPP\\mysql\\bin\\mysqldump.exe';
    
        // Ejecutamos el comando. Verifica que no tengas problemas con `exec` deshabilitado.
        $command = "\"{$mysqldumpBinary}\" --host=\"{$dbHost}\" --user=\"{$dbUser}\" --password=\"{$dbPass}\" \"{$dbName}\" > \"{$dumpPath}\"";
        exec($command);
    
        // 2) Crear el archivo ZIP que contendrá el proyecto + el dump
        $zip = new \ZipArchive();
        $backupFile = storage_path('app/backups/laravel_backup_' . date('Y-m-d_H-i-s') . '.zip');
    
        if ($zip->open($backupFile, \ZipArchive::CREATE) === TRUE) {
    
            // 2.a) Agregamos la carpeta base de Laravel al .zip
            $this->addFolderToZip(base_path(), $zip);
    
            // 2.b) Agregamos el dump .sql al .zip con un nombre "database_backup.sql"
            //      (Podrías guardarlo con el nombre $dumpPath si prefieres).
            $zip->addFile($dumpPath, 'database_backup.sql');
    
            // Cerrar el ZIP
            $zip->close();
    
            // 3) (Opcional) Borrar el archivo .sql temporal
            if (file_exists($dumpPath)) {
                unlink($dumpPath);
            }
    
            // Retornar la descarga al usuario
            return response()->download($backupFile)->deleteFileAfterSend(false);
    
        } else {
            // Si no se pudo crear el ZIP
            // (Opcional) Borramos el dump si existe
            if (file_exists($dumpPath)) {
                unlink($dumpPath);
            }
            return response()->json(['error' => 'No se pudo generar el backup'], 500);
        }
    }
    
    /**
     * Recursivamente agrega carpetas al ZIP.
     */
    private function addFolderToZip($folder, &$zip, $parentFolder = '')
    {
        $files = scandir($folder);
    
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
    
            $filePath = $folder . '/' . $file;
            $relativePath = $parentFolder . $file;
    
            if (is_dir($filePath)) {
                // Crear carpeta dentro del ZIP
                $zip->addEmptyDir($relativePath);
                // Recursión
                $this->addFolderToZip($filePath, $zip, $relativePath . '/');
            } else {
                // Agregar archivo al ZIP
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
    $request->validate([
        'nombre'        => 'required|string|max:255',
        'categoria_id'  => 'required|integer',
        'seccione_id'   => 'required|integer',
        'estado'        => 'required|integer',
        'departamento'  => 'nullable|string|max:255',
        'observacion'   => 'nullable|string',
        'llave_nombre'  => 'nullable|string|max:100',
        'cliente_id'    => 'nullable|integer',
    ]);

    if ($request->filled('llave_nombre')) {
        $llave = Llave::firstOrCreate(['nombre' => $request->llave_nombre]);
        $casilla->llaves_id = $llave->id;
    } else {
        $casilla->llaves_id = null;
    }

    $casilla->fill($request->only([
        'nombre','observacion','categoria_id','seccione_id','estado','departamento'
    ]));

    $casilla->save();

    // correo opcional
    $cliente = Cliente::find($request->cliente_id);
    if ($cliente && $cliente->email) {
        Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente));
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
