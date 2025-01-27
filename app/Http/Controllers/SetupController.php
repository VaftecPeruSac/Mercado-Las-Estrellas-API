<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banco;
use App\Models\BancoCuenta;

class SetupController extends Controller
{
    public function indexBanco(Request $request)
    {
        $per_page = 25;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $listado = Banco::select(
                'id_banco',
                'nombre','siglas',
                DB::raw("concat(siglas, ' | ', nombre) as siglas_nombre")
            )
            ->where('estado', '1')
            ->orderBy('siglas', 'asc')
            ->orderBy('nombre', 'asc');

        return $listado->paginate($per_page);
    }

    public function indexBancoCuenta(Request $request)
    {
        $per_page = 25;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $listado = BancoCuenta::select(
                'id_bancocuenta',
                'numero_cuenta'
            )
            ->where('estado', '1')
            ->orderBy('numero_cuenta', 'asc');

        if (isset($request->id_banco)) {
            $listado->where('id_banco', $request->id_banco);
        }

        return $listado->paginate($per_page);
    }
}
