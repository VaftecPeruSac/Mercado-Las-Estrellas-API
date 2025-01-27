<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'id_persona',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'correo',
        'telefono',
        'estado',
        'fecha_registro',
        'nombre_completo',
        'direccion',
        'sexo',
        'tipo_persona'
    ];
}
