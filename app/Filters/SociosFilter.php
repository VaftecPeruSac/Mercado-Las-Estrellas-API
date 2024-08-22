<?php
namespace App\Filters;
use Illuminate\Http\Request;
class  SociosFilter extends ApiFilter{

    protected $safeParams = [
        'id' => ['eq'],
    ];
    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

}