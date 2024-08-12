<?php
namespace App\Filters;
use Illuminate\Http\Request;
class  PersonaFilter extends ApiFilter{

    protected $safeParams = [
        'id' => ['eq'],
        'nombre' => ['eq'],
        'dni' => ['eq'],
        'apellidoP' => ['eq'],
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