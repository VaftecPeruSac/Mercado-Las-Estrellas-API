<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Banco;
use App\Models\BancoCuenta;
use App\Models\Modulo;
use App\Models\Usuario;

class SetupController extends Controller
{
    public function indexBanco(Request $request)
    {
        $per_page = 25;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $listado = Banco::select(
                "id_banco",
                "nombre",
                "siglas",
                DB::raw("concat(siglas, ' | ', nombre) as siglas_nombre")
            )
            ->where("estado", "1")
            ->orderBy("siglas", "asc")
            ->orderBy("nombre", "asc");

        return $listado->paginate($per_page);
    }

    public function indexBancoCuenta(Request $request)
    {
        $per_page = 25;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $listado = BancoCuenta::select(
                "id_bancocuenta",
                "numero_cuenta"
            )
            ->where("estado", "1")
            ->orderBy("numero_cuenta", "asc");

        if (isset($request->id_banco)) {
            $listado->where("id_banco", $request->id_banco);
        }

        return $listado->paginate($per_page);
    }

    public function indexModuloWeb(Request $request)
    {
        $id_usuario = $request->id_usuario ?? "";
        $usuario = Usuario::find($id_usuario);
        if(!$usuario) {
            return [];
        }
        $id_rol = $usuario->id_rol;

        $modulos = Modulo::select(
                "modulo.id_modulo",
                "modulo.nombre",
                "modulo.url",
                "modulo.url_activa",
                "modulo.icon",
                "modulo.estado",
                "modulo.id_modulo_parent",
                "modulo.orden"
            )
            ->join("rol_modulo", "modulo.id_modulo", "rol_modulo.id_modulo")
            ->where("modulo.estado", "1")
            ->where("rol_modulo.id_rol", $id_rol)
            ->whereNull("modulo.id_modulo_parent")
            ->orderBy("modulo.orden", "asc")
            ->get()
            ->map(function ($modulo) use($id_rol) {
                $modulos = Modulo::select(
                        "modulo.id_modulo",
                        "modulo.nombre",
                        "modulo.url",
                        "modulo.url_activa",
                        "modulo.icon",
                        "modulo.estado",
                        "modulo.id_modulo_parent",
                        "modulo.orden",
                        "modulo.url_foco"
                    )
                    ->join("rol_modulo", "modulo.id_modulo", "rol_modulo.id_modulo")
                    ->where("modulo.estado", "1")
                    ->where("rol_modulo.id_rol", $id_rol)
                    ->where("modulo.id_modulo_parent", $modulo->id_modulo)
                    ->orderBy("modulo.orden", "asc")
                    ->get();
                $modulo->modulos = $modulos;
                return $modulo;
            });

        return $modulos;
    }
}
