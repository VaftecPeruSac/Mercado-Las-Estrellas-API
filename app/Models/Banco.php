<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    protected $table = 'banco';
    protected $primaryKey = 'id_banco';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_banco',
        'nombre',
        'siglas',
        'estado',
    ];
}
