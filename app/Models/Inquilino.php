<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquilino extends Model
{
    use HasFactory;

    protected $fiillable = [

    ];

    public function Puesto()
    {//hasOne es para la tabla que no tiene la fk
        return $this->hasOne(Puesto::class,'id_inquilino','id_inquilino');
    }
}
