<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_socio',
        'id_documento',
        'numero_pago',
        'serie',
        'total_pago',
        'fecha_registro',
    ];

    public function Socio()
    {
        return $this->belongsTo(Socio::class, 'id_socio', 'id_socio');
    }

    public function Documento()
    {
        return $this->belongsTo(Documento::class, 'id_documento', 'id_documento');
    }

    public function DetallePagos()
    {
        return $this->hasMany(DetallePagos::class, 'id_pago', 'id_pago');
    }
}
