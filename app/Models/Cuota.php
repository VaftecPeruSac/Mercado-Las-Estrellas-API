<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    use HasFactory;
    protected $fillable = [    
        'importe',
        'fecha_vencimiento',
        'fecha_registro',
      ];
      //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
      public $timestamps = false;

    public function deudas()
    {//belongsToMany es para relaciones de muchos a muchos, va en ambas tablas
        return $this->belongsToMany(Deuda::class);
    }
}
