<?php

namespace App\Http\Controllers;

use App\Models\Alquilere;
use App\Models\Paquete;
use App\Models\Casilla;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Precio;
use App\Models\Cajero;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\Confirmationagbcmail;
use App\Models\Eventos;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AlquilereController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Alquilere::with(['Casilla', 'Cliente', 'Categoria', 'Precio', 'Cajero'])->get();

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
        $alquilere->nombre = $request->nombre; //dinero
        $alquilere->apertura = $request->apertura;
        $alquilere->habilitacion = $request->habilitacion; //dinero
        $alquilere->precio_id = $request->precio_id; //dinero
        $alquilere->cliente_id = $request->cliente_id;
        $alquilere->casilla_id = $request->casilla_id;
        $alquilere->categoria_id = $request->categoria_id;
        $alquilere->cajero_id = $request->cajero_id; // Asegúrate de que cajero_id esté llegando correctamente en la solicitud
        $alquilere->estado_pago = $request->estado_pago; //dinero
        $alquilere->ini_fecha = $request->ini_fecha;
        $alquilere->fin_fecha = $request->fin_fecha;
        $alquilere->autorizado_recojo = $request->autorizado_recojo;
        $casilla = Casilla::find($request->casilla_id);


        if ($casilla) {
            $casilla->estado = 0;
            $casilla->save();
        }
        $alquilere->save();

        // Registrar el evento usando el modelo Evento
        Eventos::create([
            'accion' => 'Alquiler de casilla',
            'cajero_id' => $alquilere->cajero_id,
            'descripcion' => 'Casilla alquilada',
            'casilla_id' => $alquilere->casilla_id,  // ✅ AHORA USA EL ID DE LA CASILLA CORRECTA
            'fecha_hora' => now(),
        ]);


        // Cargar la relación de sucursale antes de devolver la respuesta
        $alquilere->load('cajero');
        $alquilere->load('casilla');



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
        $alquilere->apertura = $alquilere->apertura;
        $alquilere->habilitacion = $alquilere->habilitacion;
        $alquilere->casilla = $alquilere->casilla;
        $alquilere->categoria = $alquilere->categoria;
        $alquilere->precio = $alquilere->precio;
        $alquilere->estado_pago = $alquilere->estado_pago;
        $alquilere->autorizado_recojo = $alquilere->autorizado_recojo;


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
        /*-----------------------------------------------------------
      Asegurarse de que siempre llegue un cajero_id:
      1) si viene en la petición: lo dejamos
      2) si no viene, tomamos el del usuario autenticado
    -----------------------------------------------------------*/
        $request->merge([
            'cajero_id' => $request->cajero_id ?? (Auth::user()->cajero->id ?? null)
        ]);

        return DB::transaction(function () use ($request, $alquilere) {

            /* === 1. RENOVACIÓN (cambio de fin_fecha) =============== */
            if ($request->fin_fecha !== $alquilere->fin_fecha) {

                // 1.a  Cerrar alquiler vigente
                $alquilere->estado    = 0;              // terminado
                $alquilere->cajero_id = $request->cajero_id;
                $alquilere->save();

                // 1.b  Crear nuevo alquiler activo
                $nuevo = new Alquilere();
                $nuevo->fill($request->only([
                    'nombre',
                    'cliente_id',
                    'apertura',
                    'habilitacion',
                    'casilla_id',
                    'categoria_id',
                    'precio_id',
                    'ini_fecha',
                    'fin_fecha',
                    'estado_pago',
                    'cajero_id'
                ]));
                $nuevo->estado = 1;                     // activo
                $nuevo->save();

                // 1.c  Actualizar casilla (si se envía casilla_estado)
                if ($casilla = Casilla::find($alquilere->casilla_id)) {
                    $casilla->estado = $request->casilla_estado ?? 0;
                    $casilla->save();
                }
  // Registrar el evento usando el modelo Evento
  Eventos::create([
    'accion' => 'Renovacion de casilla',
    'cajero_id' => $alquilere->cajero_id,
    'descripcion' => 'Renovacion de casilla',
    'casilla_id' => $alquilere->casilla_id,  // ✅ AHORA USA EL ID DE LA CASILLA CORRECTA
    'fecha_hora' => now(),
]);


// Cargar la relación de sucursale antes de devolver la respuesta
$alquilere->load('cajero');
$alquilere->load('casilla');
                return response()->json([
                    'message'   => 'Alquiler renovado correctamente',
                    'alquilere' => $nuevo
                ]);
            }

            /* === 2. EDICIÓN SIN CAMBIO DE FECHA ==================== */
            $alquilere->fill($request->only([
                'nombre',
                'apertura',
                'habilitacion',
                'cliente_id',
                'casilla_id',
                'categoria_id',
                'precio_id',
                'ini_fecha',
                'fin_fecha',
                'estado_pago',
                'cajero_id',
                'autorizado_recojo'
            ]));
            $alquilere->save();

            if ($casilla = Casilla::find($alquilere->casilla_id)) {
                $casilla->estado = $request->casilla_estado ?? $casilla->estado;
                $casilla->save();
            }
  // Registrar el evento usando el modelo Evento
  Eventos::create([
    'accion' => 'Edicion',
    'cajero_id' => $alquilere->cajero_id,
    'descripcion' => 'Edicion de casilla',
    'casilla_id' => $alquilere->casilla_id,  // ✅ AHORA USA EL ID DE LA CASILLA CORRECTA
    'fecha_hora' => now(),
]);


// Cargar la relación de sucursale antes de devolver la respuesta
$alquilere->load('cajero');
$alquilere->load('casilla');
            return response()->json([
                'message'   => 'Alquiler actualizado correctamente',
                'alquilere' => $alquilere
            ]);
        });
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

    public function updateCasillasSeleccionadas(Request $request)
    {

        // Validar el request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:alquileres,id', // Asegura que cada id exista en la tabla alquileres
        ]);

        $alquileres = Alquilere::whereIn('id', $request->ids)->get();
        $errors = [];

        foreach ($alquileres as $alquilere) {
            try {
                // Actualizar el estado de la casilla asociada
                $casilla = Casilla::find($alquilere->casilla_id);
                if ($casilla) {
                    $casilla->estado = 2; // Cambiar estado a "Con Correspondencia"
                    $casilla->save();
                }

                $cliente = $alquilere->cliente;
                if ($cliente) {
                    Mail::to($cliente->email)->send(new Confirmationagbcmail($cliente));
                } else {
                    $errors[] = "Cliente no encontrado para alquiler ID: " . $alquilere->id;
                }
            } catch (\Exception $e) {
                $errors[] = "Error actualizando alquiler ID: " . $alquilere->id . " - " . $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            return response()->json(['status' => 'error', 'message' => $errors], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Casillas actualizadas y correos enviados con éxito']);
    }

    public function updateAllToOcupadas()
    {

        try {
            // Obtener todas las casillas en estado "Con Correspondencia" (estado 2)
            $alquileres = Alquilere::whereHas('casilla', function ($query) {
                $query->where('estado', 2);
            })->get();


            $errors = [];

            foreach ($alquileres as $alquilere) {
                try {
                    // Actualizar el estado de la casilla asociada
                    $casilla = Casilla::find($alquilere->casilla_id);
                    if ($casilla) {
                        $casilla->estado = 0; // Cambiar estado a "Ocupado"
                        $casilla->save();
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error actualizando alquiler ID: " . $alquilere->id . " - " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                return response()->json(['status' => 'error', 'message' => $errors], 500);
            }


            return response()->json(['status' => 'success', 'message' => 'Todas las casillas "Con Correspondencia" han sido actualizadas a "Ocupado" y correos enviados con éxito']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error en el proceso de actualización a "Ocupadas"'], 500);
        }
    }
    public function getCasillasByEstado($estado)
    {
        $casillas = Casilla::where('estado', $estado)->get();

        $result = $casillas->map(function ($casilla) {
            $alquiler = Alquilere::with(['cliente', 'categoria', 'precio', 'cajero'])
                ->where('casilla_id', $casilla->id)
                ->first();

            return [
                'casilla' => $casilla,
                'alquiler' => $alquiler
            ];
        });

        return response()->json($result);
    }

    public function getCasillasOcupadas()
    {
        return $this->getCasillasByEstado(0);
    }

    public function getCasillasLibres()
    {
        return $this->getCasillasByEstado(1);
    }

    public function getCasillasConCorrespondencia()
    {
        return $this->getCasillasByEstado(2);
    }

    public function getCasillasMantenimiento()
    {
        return $this->getCasillasByEstado(3);
    }

    public function getCasillasVencidas()
    {
        return $this->getCasillasByEstado(4);
    }

    public function getCasillasReservadas()
    {
        return $this->getCasillasByEstado(5);
    }
}
