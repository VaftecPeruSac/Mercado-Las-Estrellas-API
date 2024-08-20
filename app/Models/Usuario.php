<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $fillable = [
      'id_persona',
      'nombre_usuario',
      'contrasenia',
      'rol',
      'estado',
      'fecha_registro',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;

    //antes tenia relacion con socio y puesto
     public function Persona()
     {//belongsTo es para la tabla que tiene la fk
        return $this->belongsTo(Persona::class,'id_persona','id_persona');
     }

     public function Socio()
     {//hasOne es para la tabla que no tiene la fk
        return $this->hasOne(Socio::class,'id_usuario','id_usuario');
     }
}
