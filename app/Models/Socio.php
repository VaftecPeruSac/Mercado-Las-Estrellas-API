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
        return $this->hasOne(Persona::class);
    }

    public function Puesto()
    {
        return $this->hasMany(Puesto::class);
    }
    
    public function GiroNegocio()
    {
        return $this->hasOne(GiroNegocio::class);
    }   
}
