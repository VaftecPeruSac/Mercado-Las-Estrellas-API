<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    protected $table = 'deudas';
    protected $primaryKey = 'id_deuda';
    public $timestamps = false;

    protected $fillable = [
        'id_socio',
        'id_puesto',
        'total_deuda',
        'fecha_registro',
    ];

    public function Socio()
    {
        return $this->belongsTo(Socio::class, 'id_socio');
    }

    public function Puesto()
    {
        return $this->belongsTo(Puesto::class, 'id_puesto');
    }
}
