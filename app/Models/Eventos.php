<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'apertura',
        'habilitacion',
        'precio_id',
        'cliente_id',
        'casilla_id',
        'categoria_id',
        'cajero_id',
        'estado_pago',
        'ini_fecha',
        'fin_fecha',
        'autorizado_recojo',
        'estado',           // lo usas al renovar
        'accion',
        'descripcion',
         'casilla',
        'fecha_hora',
        'cajero_id',
        
    ];
    public function Cajero(){
        return $this->belongsTo(Cajero::class);
    }
    public function Casilla(){
        return $this->belongsTo(Casilla::class);
    }
}
