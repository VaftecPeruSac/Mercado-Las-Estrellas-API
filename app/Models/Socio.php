<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

    protected $fillable = [    
      'id_usuario',
      'saldo',
      'estado',
      'fecha_registro',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;
    public function Usuario()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Usuario::class,'id_usuario','id_usuario');
    }

    public function Deuda()
    {//hasOne es para la tabla que no tiene la fk
       return $this->hasOne(Deuda::class,'id_socio','id_socio');
    }
    
    public function Puesto()
    {//hasOne es para la tabla que no tiene la fk
       return $this->hasOne(Puesto::class,'id_socio','id_socio');
    }  
}
