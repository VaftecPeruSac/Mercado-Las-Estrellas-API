<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persona extends Model
{
    use HasFactory;
    protected $fillable = [

    ];
     public function Socio()
     {
        return $this->hasOne(Socio::class,'id_persona','id');
     }
}
