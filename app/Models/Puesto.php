<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $fillable = [    
        'id_socio',
        'id_gironegocio',
        'id_block',
        'area',
        'id_inquilino',
        'estado',
        'fecha_registro',
      ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
    public $timestamps = false;
    public function Socio()
    {//belongsTo es para la tabla que tiene la fk
       return $this->belongsTo(Socio::class,'id_socio','id_socio');
    }
    public function Block()
    {
        return $this->hasOne(Block::class,'id_block','id_block');
    }
    
    public function Gironegocio()
    {
        return $this->hasOne(GiroNegocio::class,'id_gironegocio','id_gironegocio');
    }
    
    public function Inquilino()
    {
        return $this->belongsTo(Inquilino::class,'id_inquilino','id_inquilino');
    }
}
