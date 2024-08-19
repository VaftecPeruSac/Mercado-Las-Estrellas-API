<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $fiillable = [

    ];

    public function Socio()
    {
        return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }
    public function Block()
    {
        return $this->hasOne(Block::class,'id_block','id_block');
    }
    
    public function Deuda()
    {
        return $this->belongsTo(Deuda::class,'id_puesto','id_puesto');
    }

    public function Inquilino()
    {
        return $this->belongsTo(Persona::class,'id_inquilino','id_persona');
    }
}
