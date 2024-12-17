<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaCuota extends Model
{
    use HasFactory;    

    protected $table = 'deuda_cuotas';
    protected $primaryKey = 'id_deuda_cuota';
    public $timestamps = false;

    protected $fillable = [
        'id_deuda',
        'id_cuota',
        'a_cuenta',
        'estado',
    ];

    public function Cuota()
    {
        return $this->belongsTo(Cuota::class, 'id_cuota');
    }

    public function Deuda()
    {
        return $this->belongsTo(Deuda::class, 'id_deuda');
    }
}
