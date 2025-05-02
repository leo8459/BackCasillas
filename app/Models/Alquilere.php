<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquilere extends Model
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
    ];
    public function Cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function Casilla(){
        return $this->belongsTo(Casilla::class);
    }
    public function Categoria(){
        return $this->belongsTo(Categoria::class);
    }
    public function Precio(){
        return $this->belongsTo(Precio::class);
    }
    public function Cajero(){
        return $this->belongsTo(Cajero::class);
    }
    public function llaves(){
        return $this->belongsTo(llaves::class);
    }
    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }
}
