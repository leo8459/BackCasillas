<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    use HasFactory;
    public function alquilere()
    {
        return $this->belongsTo(Alquilere::class);
    }
}
