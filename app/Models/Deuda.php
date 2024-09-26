<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_deuda';
    protected $fillable = [
        'id_deuda',
        'id_socio',
        'id_cuota',
        'id_puesto',
        'id_servicio',
        'total_deuda',
        'fecha_registro',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;

    public function Socio()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }

    // public function Cuota()
    // {//belongsToMany es para relaciones de muchos a muchos, va en ambas tablas
    //     return $this->belongsToMany(Cuota::class,'deuda_cuotas','id_deuda','id_cuota')
    //     ->withPivot('a_cuenta');
    // }
    public function Cuota()
    {
       return $this->belongsTo(Cuota::class,'id_cuota','id_cuota');
    }

    public function Persona()
    {
       return $this->belongsTo(Persona::class,'id_socio','id_persona');
    }

    public function Puesto()
    {
       return $this->belongsTo(Puesto::class,'id_puesto','id_puesto');
    }

    public function Servicio()
    {
       return $this->belongsTo(Servicio::class,'id_servicio','id_servicio');
    }
}
