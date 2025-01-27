<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancoCuenta extends Model
{
    use HasFactory;

    protected $table = 'banco_cuenta';
    protected $primaryKey = 'id_bancocuenta';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_bancocuenta',
        'numero_cuenta',
        'id_banco',
        'estado',
    ];
}
