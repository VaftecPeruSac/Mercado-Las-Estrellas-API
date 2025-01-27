<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoBanco extends Model
{
    use HasFactory;

    protected $table = 'pago_banco';
    protected $primaryKey = 'id_pagobanco';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_pagobanco',
        'id_banco',
        'id_bancocuenta',
        'numero_operacion',
        'fecha_operacion',
    ];
}
