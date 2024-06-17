<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    public function Categoria(){
        return $this->belongsTo(Categoria::class);
    }
}
