<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Deuda;
use App\Http\Resources\ReportePagoCollection;
use App\Http\Resources\ReporteDeudaCollection;
use App\Http\Resources\ReporteCuotaPorMetroCollection;
use App\Http\Resources\ReporteCuotaPorPuestoCollection;
use Illuminate\Http\Request;

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
        // $response = new ReportePagoCollection($paginate);

        // return response()->json($response);
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
        // $response = new ReporteDeudaCollection($paginate);

        // return response()->json($response);
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
        // $response = new ReporteCuotaPorMetroCollection($paginate);

        // return response()->json($response);
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
        // $response = new ReporteCuotaPorPuestoCollection($paginate);

        // return response()->json($response);
        return new ReporteCuotaPorPuestoCollection($paginate);
    }
}
