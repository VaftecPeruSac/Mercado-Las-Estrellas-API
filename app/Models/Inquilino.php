<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquilino extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_inquilino';
    public $timestamps = false;
    protected $fillable = [    
        'nombre_completo',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'telefono',
    ];

    public function Puesto()
    {
        return $this->hasOne(Puesto::class,'id_inquilino','id_inquilino');
    }
}
