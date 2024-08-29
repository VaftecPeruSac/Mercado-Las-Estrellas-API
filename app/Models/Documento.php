<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $fillable = [    
        'numero_documento',
        'serie',
        'estado',
        'fecha_registro',
      ];
      //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
      public $timestamps = false;

    public function Pago()
    {//hasOne es para la tabla que no tiene la fk
       return $this->hasOne(Pago::class,'id_documento','id_documento');
    }
}
