<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pago';
    public $timestamps = false;
    protected $fillable = [    
        'id_pago',
        'id_socio',
        'id_documento',
        'numero_pago',
        'serie',
        'total_pago',
        'fecha_registro',
    ];

    public function Socio()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }

    public function Documento()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Documento::class,'id_documento','id_documento');
    }
}
