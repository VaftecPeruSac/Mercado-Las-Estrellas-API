<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $fiillable = [

    ];
    // public function Socio()
    // {
    //     return $this->belongsTo( Socio::class,'id_socio','id');
    // }

    public function Socio()
    {
        return $this->belongsTo(Socio::class,'id_socio','id');
    }
    public function Block()
    {
        return $this->hasOne(Block::class);
    }
}
