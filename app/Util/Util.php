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
}
