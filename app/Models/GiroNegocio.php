<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiroNegocio extends Model
{
    use HasFactory;

    protected $table = 'giro_negocios';
    protected $primaryKey = 'id_gironegocio';
    public $timestamps = false;

    protected $fillable = [    
        'nombre',
    ];

    public function Puesto()
    {
        return $this->belongsTo(Puesto::class,'id_gironegocio','id_gironegocio');
    }
}
