<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupAnio extends Model
{
    use HasFactory;

    protected $table = 'setup_anio';
    protected $primaryKey = 'id_anio';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_anio',
        'nombre',
    ];
}
