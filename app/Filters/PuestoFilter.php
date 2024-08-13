<?php
namespace App\Filters;
use Illuminate\Http\Request;
class  PuestoFilter extends ApiFilter{

    protected $safeParams = [
        'id' => ['eq'],
        'puesto' => ['eq'],
        'id_socio' => ['eq'],
        'id_bloque' => ['eq'],
        'area' => ['eq'],
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