<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llave extends Model
{
    use HasFactory;

    protected $fillable = ['nombre']; // <-- habilita asignación masiva
    // o: protected $guarded = [];  // (permite todo, pero con cuidado)
}
