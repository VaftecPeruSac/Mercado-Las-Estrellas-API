<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiroNegocio extends Model
{
    use HasFactory;

    protected $fiillable = [

    ];

    public function Socio()
    {
        return $this->hasOne(Socio::class);
    }
}
