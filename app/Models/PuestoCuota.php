<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuestoCuota extends Model
{
  use HasFactory;

  protected $table = 'puesto_cuotas';
  protected $primaryKey = 'id_puesto_cuota';
  public $timestamps = false;

  protected $fillable = [
    'id_puesto',
    'id_cuota',
    'estado'
  ];

  public function puesto()
  {
    return $this->belongsTo(Puesto::class, 'id_puesto');
  }

  public function cuota()
  {
    return $this->belongsTo(Cuota::class, 'id_cuota');
  }
}
