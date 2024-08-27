<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquilino extends Model
{
    protected $primaryKey = 'id_inquilino'; 
    use HasFactory;
    protected $fillable = [    
        'nombre_completo',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'telefono',
      ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;

    public function Puesto()
    {//hasOne es para la tabla que no tiene la fk
        return $this->hasOne(Puesto::class,'id_inquilino','id_inquilino');
    }
}
