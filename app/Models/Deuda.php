<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;
    protected $fillable = [    
        'id_socio',
        'total_deuda',
        'fecha_registro',
      ];
      //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
      public $timestamps = false;
    public function Socio()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }
    public function cuotas()
    {//belongsToMany es para relaciones de muchos a muchos, va en ambas tablas
        return $this->belongsToMany(Cuota::class,'deuda_cuotas','id_deuda','id_cuota')
        ->withPivot('a_cuenta');
    }

}
