<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puesto extends Model
{
    use HasFactory;
    protected $fiillable = [

    ];
    public function Socio()
    {
        return $this->belongsTo( Socio::class);
    }

    public function Block()
    {
        return $this->hasOne(Block::class);
    }
}
