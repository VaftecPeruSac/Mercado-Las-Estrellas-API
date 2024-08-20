<?php
namespace App\Filters;
use Illuminate\Http\Request;
class  UsuarioFilter extends ApiFilter{

    protected $safeParams = [
        'id' => ['eq'],
        'nombre_usuario' => ['eq'],
        'estado' => ['eq'],
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}