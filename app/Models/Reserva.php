<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    public function Cliente(){
        return $this->belongsTo(Cliente::class);
    }
    public function Casilla(){
        return $this->belongsTo(Casilla::class);
    }
}
