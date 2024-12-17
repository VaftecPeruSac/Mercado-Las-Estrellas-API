<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Socio extends Model
{
    use HasFactory;

    protected $table = 'socios';
    protected $primaryKey = 'id_socio';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'dni',
        'correo',
        'telefono',
        'direccion',
        'sexo',
        'fecha_registro'
    ];

    public function Usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_socio', 'id_usuario');
    }

    public function Deuda()
    {
        return $this->hasOne(Deuda::class, 'id_socio', 'id_socio');
    }

    public function Puesto()
    {
        return $this->hasOne(Puesto::class, 'id_socio', 'id_socio');
    }

    public function Pago()
    {
        return $this->hasOne(Pago::class, 'id_socio', 'id_socio');
    }
}
