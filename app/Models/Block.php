<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{    
    use HasFactory;
    protected $fillable = [    
        'nombre',
      ];
          //para no usar los timestamps en el migrate, de lo contrario requiere ello y no permite hacer registros
          public $timestamps = false;

    public function Puesto()
    {
        return $this->hasOne(Puesto::class);
    }
}
