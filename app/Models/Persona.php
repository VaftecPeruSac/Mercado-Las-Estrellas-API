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
      'id_usuarioregistro',
      'fecha_registro',
    ];

    public $timestamps = false;
     public function Socio()
     {
        return $this->hasOne(Socio::class,'id_persona','id_persona');
     }

     public function Puesto()//para inquilino
     {
         return $this->hasOne(Persona::class,'id_inquilino','id_persona');
     }
}
