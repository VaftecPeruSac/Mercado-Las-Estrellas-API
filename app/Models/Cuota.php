<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuota extends Model
{
    //se declara la pk en el model con el nombre que tiene en la bd porque laravel define el id de una tabla como "id"
    protected $primaryKey = 'id_cuota';
    use HasFactory;
    protected $fillable = [
        'importe',
        'fecha_vencimiento',
        'fecha_registro',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;

    public function deudas()
    { //belongsToMany es para relaciones de muchos a muchos, va en ambas tablas
        return $this->belongsToMany(Deuda::class);
    }
    public function servicios()
    { //belongsToMany es para relaciones de muchos a muchos, va en ambas tablas
        return $this->belongsToMany(Servicio::class, 'cuota_servicios', 'id_servicio', 'id_cuota');
    }
}
