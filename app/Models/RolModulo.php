<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolModulo extends Model
{
    protected $table = 'rol_modulo';
    protected $primaryKey = ['id_modulo', 'id_rol'];
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id_rol',
        'id_modulo'
    ];

    protected function setKeysForSaveQuery($query)
    {
        foreach ($this->getKeyName() as $key) {
            $query->where($key, $this->original[$key]);
        }
        return $query;
    }

    protected function getKeyForSaveQuery()
    {
        return $this->getKeyName();
    }
}
