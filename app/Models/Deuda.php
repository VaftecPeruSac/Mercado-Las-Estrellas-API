<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;
    public function Socio()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }
}
