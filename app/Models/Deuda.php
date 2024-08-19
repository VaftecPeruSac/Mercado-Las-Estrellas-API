<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    public function Deuda()
    {
        return $this->hasOne(Puesto::class,'id_puesto','id_puesto');
    }
}
