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
     public function puesto(): HasMany
     {
        return $this->hasMany(Puesto::class, 'foreign_key', 'local_key');
     }
}
