<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulo';
    protected $primaryKey = 'id_modulo';
    public $timestamps = false;

    protected $fillable = [
        'id_modulo',
        'nombre',
        'url',
        'url_foco',
        'url_activa',
        'icon',
        'estado',
        'id_modulo_parent',
        'orden'
    ];
}
