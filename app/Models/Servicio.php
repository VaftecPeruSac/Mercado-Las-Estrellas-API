<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;
    protected $fillable = [    
        'descripcion',
        'costo_unitario',
        'tipo_servicio',
        'estado',
        'fecha_registro',
      ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;
}
