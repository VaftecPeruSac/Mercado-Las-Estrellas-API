<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_usuario',
        'rol',
        'nombre_usuario',
        'contrasenia',
        'estado',
        'token',
        'fecha_registro',
    ];

    public function Socio()
    {
        return $this->hasOne(Socio::class, 'id_usuario', 'id_usuario');
    }
}
