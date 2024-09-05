<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaCuota extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_deuda',
        'id_cuota',
        'a_cuenta',
        'estado',
    ];
    //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros

    public $timestamps = false;

    public function Cuota()
    { //belongsTo es para la tabla que tiene la fk
        return $this->belongsTo(Cuota::class, 'id_cuota', 'id_cuota');
    }

    public function Deuda()
    { //belongsTo es para la tabla que tiene la fk
        return $this->belongsTo(Deuda::class, 'id_deuda', 'id_deuda');
    }
}
