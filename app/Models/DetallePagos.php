<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePagos extends Model
{
    use HasFactory;

    protected $table = 'detalle_pagos';
    protected $primaryKey = 'id_detallepago';
    public $timestamps = false;

    protected $fillable = [
        'id_pago',
        'id_cuota',
        'id_deuda',
        'id_puesto',
        'importe',
        'id_servicio',
        'id_deuda_cuota'
    ];

    public function Pago()
    {
        return $this->belongsTo(Pago::class,'id_pago');
    }

    public function Cuota()
    {
        return $this->belongsTo(Cuota::class,'id_cuota');
    }

    public function Deuda()
    {
        return $this->belongsTo(Deuda::class,'id_deuda');
    }

    public function Puesto()
    {
        return $this->belongsTo(Puesto::class,'id_puesto');
    }

    public function Servicio()
    {
        return $this->belongsTo(Servicio::class,'id_servicio');
    }
}
