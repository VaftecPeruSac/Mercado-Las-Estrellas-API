<?php

namespace App\Util;

class Util
{
    public static function compareDateYear($fecha,$anho)
    {
        return "year($fecha) = $anho";
    }

    public static function compareDateMonth($fecha,$mes)
    {
        return "month($fecha) = $mes";
    }

    public static function sumaColArrayObjFormat($arrayObject, $columna)
    {
        $total = array_sum(array_column($arrayObject, $columna));
        return number_format($total, 2, '.', '');
    }
}
