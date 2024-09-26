<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquetes extends Model
{
    use HasFactory;
    public function alquilere()
    {
        return $this->belongsTo(Alquilere::class);
    }
}
