<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';
    protected $primaryKey = 'id_servicio';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'costo_unitario',
        'tipo_servicio',
        'fecha_registro',
        'activo'
    ];

    public function Cuotas()
    {
        return $this->belongsToMany(Cuota::class, 'cuota_servicios', 'id_servicio', 'id_cuota');
    }
}
