<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Casilla extends Model
{
    use HasFactory;
    public function Categoria(){
        return $this->belongsTo(Categoria::class);
    }
    public function Seccione(){
        return $this->belongsTo(Seccione::class);
    }
    public function llaves(){
        return $this->belongsTo(Llave::class, 'llaves_id');
    }
    public function Cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function alquilere()
    {
        return $this->hasOne(Alquilere::class);
    }
    
}
