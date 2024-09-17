<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{    
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [    
        'nombre',
    ];

    public function Puesto()
    {
        return $this->hasOne(Puesto::class);
    }
}
