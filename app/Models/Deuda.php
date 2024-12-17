<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    protected $table = 'deudas';
    protected $primaryKey = 'id_deuda';
    public $timestamps = false;

    protected $fillable = [
        'id_socio',
        'id_cuota',
        'id_puesto',
        'id_servicio',
        'total_deuda',
        'fecha_registro',
    ];

    public function Socio()
    {
        return $this->belongsTo(Socio::class, 'id_socio');
    }

    public function Cuota()
    {
        return $this->belongsTo(Cuota::class, 'id_cuota');
    }

    public function Puesto()
    {
        return $this->belongsTo(Puesto::class, 'id_puesto');
    }

    public function Servicio()
    {
        return $this->belongsTo(Servicio::class, 'id_servicio');
    }
}
