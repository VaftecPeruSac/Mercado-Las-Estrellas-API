<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetupMes extends Model
{
    use HasFactory;

    protected $table = 'setup_mes';
    protected $primaryKey = 'id_mes';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_mes',
        'nombre',
    ];
}
