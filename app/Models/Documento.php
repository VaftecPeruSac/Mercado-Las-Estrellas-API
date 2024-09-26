<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_documento';
    public $timestamps = false;
    protected $fillable = [
        'id_documento',
        'numero_documento',
        'serie',
        'estado',
        'fecha_registro',
    ];

    public function Pago()
    {
       return $this->hasOne(Pago::class,'id_documento','id_documento');
    }
}
