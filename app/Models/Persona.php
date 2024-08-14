<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $fillable = [
      'nombre',
      'apellidoP',
      'apellidoM',
      'dni',
      'estado',

    ];
     public function Socio()
     {
        return $this->hasOne(Socio::class,'id','id_persona');
     }
}
