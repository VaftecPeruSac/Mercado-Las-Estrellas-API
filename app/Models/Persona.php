<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_persona';
    public $timestamps = false;
    protected $fillable = [
      'id_persona',
      'nombre',
      'apellido_paterno',
      'apellido_materno',
      'dni',
      'correo',
      'telefono',
      'direccion',
      'sexo',
      'estado',
      'fecha_registro',
      'nombre_completo',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    // public $timestamps = false;

    //antes tenia relacion con socio y puesto
     public function Usuario()
     {
        return $this->hasOne(Usuario::class,'id_persona','id_persona');
     }

}
