<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePagos extends Model
{
    // use HasFactory;
    protected $table = 'detalle_pagos';
    protected $primaryKey = 'id_detallepago';
    public $timestamps = false;
    protected $fillable = [
        'id_detallepago',
        'id_pago',
        'id_cuota',
        'id_puesto',
        'importe',
        'fecha_registro',
    ];

    // public $timestamps = false;

}
