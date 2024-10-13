<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Deuda;
use App\Http\Resources\ReportePagoCollection;
use App\Http\Resources\ReporteDeudaCollection;
use App\Http\Resources\ReporteCuotaPorMetroCollection;
use App\Http\Resources\ReporteCuotaPorPuestoCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function pagos(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Pago::where('id_socio', $request->id_socio)
            ->paginate($per_page);

        return new ReportePagoCollection($paginate);
    }

    public function deudas(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_puesto', $request->id_puesto)
            ->paginate($per_page);

        return new ReporteDeudaCollection($paginate);
    }

    public function cuotaPorMetros(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_cuota', $request->id_cuota)
            ->paginate($per_page);

        return new ReporteCuotaPorMetroCollection($paginate);
    }

    public function cuotaPorPuestos(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_puesto', $request->id_puesto)
            ->paginate($per_page);

        return new ReporteCuotaPorPuestoCollection($paginate);
    }

    public function dashboard(Request $request)
    {
        $cantidadSociosActivos = DB::table('socios')
            ->where('estado', '1')->count('*');
        $acumulacionPagos = DB::table('pagos')
            ->whereRaw("month(fecha_registro) = month(now())")->sum('total_pago');
        $acumulacionDeudas = DB::table('deudas')
            ->whereRaw("month(fecha_registro) = month(now())")->sum('total_deuda');

        $acumulacionPagos = number_format($acumulacionPagos, 2, '.', ',');
        $acumulacionDeudas = number_format($acumulacionDeudas, 2, '.', ',');

        $response = [
            'acumulacion_deuda' => $acumulacionDeudas,
            'acumulacion_pago' => $acumulacionPagos,
            'cantidad_socios_activos' => $cantidadSociosActivos,
        ];
        return response()->json($response);
    }
}
