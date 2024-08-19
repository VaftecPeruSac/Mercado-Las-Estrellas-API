<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

    protected $fiillable = [    

    ];

    public function Persona()
    {
        return $this->hasOne(Persona::class,'id_persona','id_persona'); //belong to se coloca en la tabla que lleva la fk
    }

    // public function Puesto()
    // {
    //     return $this->hasMany(Puesto::class,'id_socio','id');
    // }

    public function Puestos()
    {
        return $this->hasOne(Puesto::class,'id_socio','id_socio');
    }
    
    public function GiroNegocio()
    {
        return $this->belongsTo(GiroNegocio::class,'id_gironegocio','id_gironegocio');
    }   
}
