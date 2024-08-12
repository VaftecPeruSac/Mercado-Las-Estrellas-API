<?php
namespace App\Filters;
use Illuminate\Http\Request;
class  SociosFilter extends ApiFilter{

    protected $safeParams = [
        'id' => ['eq'],
        'id_persona' => ['eq'],
        'correo' => ['eq'],
        'telefono' => ['eq'],
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}