<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuotaServicios extends Model
{
    use HasFactory;

    protected $table = 'cuota_servicios';
    protected $primaryKey = 'id_cuota_servicio';
    public $timestamps = false;

    protected $fillable = [
        'id_cuota',
        'id_servicio',
        'importe',
    ];

    public function cuota()
    {
        return $this->belongsTo(Cuota::class, 'id_cuota');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }

    public function deudaCuotas()
    {
        return $this->hasMany(DeudaCuota::class, 'id_cuota_servicio');
    }
}
